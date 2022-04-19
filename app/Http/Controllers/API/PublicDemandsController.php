<?php

namespace App\Http\Controllers\API;

use App\Demand;
use App\DemandAttachment;
use App\DemandFile;
use App\Detail;
use App\Events\DemandRegistered;
use App\Http\Controllers\Controller;
use App\Notifications\demands\DemandSentNotification;
use App\Notifications\RelaunchDemandNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class PublicDemandsController extends Controller
{
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'subject' => 'required|min:2|max:255',
            'name' => 'required|min:2|max:255',
            'email' => 'required|email|min:2|max:255',
            'phone' => 'required|string',
            'city' => 'required|min:2|max:255',
            'message' => 'required|min:10',
            'categories' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['validation_errors' => $validator->errors()]);
        }

        $validated_demand = $validator->valid();

        $validated_demand['lat'] = $request->input('lat');
        $validated_demand['lng'] = $request->input('lng');
        $validated_demand['administrative'] = $request->input('administrative');

        $validated_demand['categories'] = (explode(',', $validated_demand['categories']));
        $final_categories = collect($validated_demand['categories'])->map(function ($item) {
            return (int) $item;
        });

        if ($final_categories->count() < 1) {
            return response()->json(['result' => false]);
        }

        $demand = new Demand();
        $demand->user_id = null;
        $demand->uuid = $this->generateUUID();
        $demand->subject = $validated_demand['subject'];
        $demand->name = $validated_demand['name'];
        $demand->email = $validated_demand['email'];
        $demand->phone = $validated_demand['phone'];
        $demand->city = $validated_demand['city'];
        $demand->message = $validated_demand['message'];
        $demand->lat = $validated_demand['lat'];
        $demand->lng = $validated_demand['lng'];
        $demand->administrative = $validated_demand['administrative'];

        DB::beginTransaction();

        if (!$demand->save()) {
            DB::rollBack();
            return response()->json(['result' => false]);
        }

        if ($request->hasFile('the_files')) {

            $all_files = $request->file('the_files');
            foreach ($all_files as $one_file) {
                $infos = $this->uploadFile($one_file);

                $demandFile = new DemandFile();
                $demandFile->demand_id = $demand->id;
                $demandFile->user_id = null;
                $demandFile->name = $infos['file_name'];
                $demandFile->extension = $infos['ext'];
                // $demandFile->path = null;
                $demandFile->mime_type = $infos['mime_type'];

                if (!$demandFile->save()) {
                    DB::rollBack();
                }
            }

            // $quote['files'] = $quote->files;
        }

        if ($request->hasFile('the_attachments')) {

            $all_files = $request->file('the_attachments');
            foreach ($all_files as $one_file) {
                $infos = $this->uploadAttachment($one_file);

                $demandAttachment = new DemandAttachment();
                $demandAttachment->demand_id = $demand->id;
                $demandAttachment->user_id = null;
                $demandAttachment->name = $infos['file_name'];
                if ($infos['initial_name']) {
                    $demandAttachment->initial_name = $infos['initial_name'];
                }
                $demandAttachment->extension = $infos['ext'];
                // $demandAttachment->path = null;
                $demandAttachment->mime_type = $infos['mime_type'];

                if (!$demandAttachment->save()) {
                    DB::rollBack();
                }
            }

            // $quote['files'] = $quote->files;
        }

        $detail = new Detail;
        $detail->demand_id = $demand->id;
        $detail->status = 0;
        $detail->offers = 3;
        $detail->unique = Str::uuid();
        if (!$detail->save()) {
            DB::rollBack();
        }

        if (!$demand->categories()->sync(json_decode($final_categories))) {
            DB::rollBack();
        }

        Notification::route('mail', $demand->email)->notify(new DemandSentNotification($demand));

        DB::commit();

        event(new DemandRegistered($demand));

        return response()->json(['result' => true]);

        // return response('ok - response');
    }

    public function relaunch($uuid, $unique)
    {

        $demand = Demand::where('uuid', $uuid)->first();

        if (!$demand) {
            return response()->json(['errors' => 'Cerere invalida.']);
        }

        if ($demand->detail->unique != $unique) {
            return response()->json(['errors' => 'Cerere invalida.']);
        }

        $demand->detail->offers += 3;
        // $demand->detail->unique = md5(Str::uuid());

        if (!$demand->detail->save()) {
            return response()->json(['errors' => 'Am intampinat probleme. Incercati mai tarziu.']);
        }

        // setare ca activa
        if ($demand->state !== 1) {
            $demand->state = 1;
            $demand->save();
        }

        // notificare owner.
        Notification::route('mail', $demand->email)
            ->notify(new RelaunchDemandNotification($demand));

        $final_demand = \App\Demand::find($demand->id);
        $final_demand->detail;
        $final_demand->buyers;
        $final_demand->buyers = $final_demand->buyers->map(function ($item) {
            $item['complete_name'] = $item->user->getTheName();
            $item->makeHidden(['user']);
            return $item;
        });
        $final_demand->buyers->makeHidden(['amount_paid']);
        $final_demand->categories;
        $final_demand->categories->makeHidden(['price', 'pivot']);
        $final_demand->files;

        return response()->json(['success' => true, 'demand' => $final_demand]);
    }

    public function delete($uuid, $unique)
    {
        $demand = Demand::where('uuid', $uuid)->first();

        if (!$demand) {
            return response()->json(['errors' => 'Cerere invalida.']);
        }

        if ($demand->detail->unique != $unique) {
            return response()->json(['errors' => 'Cerere invalida.']);
        }

        DB::beginTransaction();

        if ($demand->reports && $demand->reports->count() > 0) {
            if (!$demand->reports()->delete()) {
                DB::rollback();
            }
        }

        if ($demand->prospects && $demand->prospects()->count() > 0) {
            if (!$demand->prospects()->delete()) {
                DB::rollback();
            }
        }

        if ($demand->buyers && $demand->buyers()->count() > 0) {
            if (!$demand->buyers()->delete()) {
                DB::rollback();
            }
        }

        // detach categories
        if (!$demand->categories()->detach()) {
            DB::rollBack();
        }

        if ($demand->detail) {
            if (!$demand->detail()->delete()) {
                DB::rollBack();
            }
        }

        // Delete timelines?

        // delete files
        if ($demand->files && $demand->files->count() > 0) {

            foreach ($demand->files as $theFile) {
                $theFile->delete();
            }
        }

        if ($demand->attachments && $demand->attachments->count() > 0) {

            foreach ($demand->attachments as $theFile) {
                $pathToFile = 'uploads/demands/' . $theFile->name;
                if (Storage::disk('do_spaces')->exists($pathToFile)) {
                    Storage::disk('do_spaces')->delete($pathToFile);
                }
                $theFile->delete();
            }
        }

        if (!$demand->delete()) {
            DB::rollback();
            return response()->json(['errors' => 'Cerere invalida.']);
        }

        DB::commit();

        return response()->json(['success' => true]);
    }

    private function generateUUID()
    {
        // genereaza id
        $res = \Illuminate\Support\Str::uuid();
        // $res = rand(0, 99);
        $id = substr($res, 0, 8);

        // echo 'SUS: ' . $id . '<br/>';
        // verifica daca exista in db
        while (\App\Demand::where('uuid', $id)->get()->count() > 0) {
            // regenereaza daca exista
            $res = \Illuminate\Support\Str::uuid();
            // $res = rand(0, 99);
            $id = substr($res, 0, 8);
            // echo $id . '<br/>';
        }

        // echo 'JOS: ' . $id . '<br/>';
        return $id;

        // => id este unic si poate fi atasat unei cereri.
    }

    protected function uploadFile($file)
    {

        // Get image original extension
        $ext = $file->getClientOriginalExtension();
        $mimeType = $file->getClientMimeType();
        $random = substr(md5(mt_rand()), 0, 5);

        // dd($mimeType);

        // Compose the name
        $file_name = 'demand-file-' . time() . "-" . $random . '.' . $ext;

        $infos = [
            'ext' => $ext,
            'mime_type' => $mimeType,
            'file_name' => $file_name,
        ];

        // Save file
        // Image::make($file)
        // ->resize(300, 300, function ($constraint) {
        //     $constraint->aspectRatio();
        // })
        // ->save(public_path('images/avatars/' . $file_name));

        // if file is image

        // if (!File::isDirectory(storage_path('app/public') . '/demands')) {
        //     File::makeDirectory(storage_path('app/public') . '/demands', 0777, true, true);
        // }
        // if (!File::isDirectory(public_path('storage/demands'))) {
        //     File::makeDirectory(public_path('storage/demands'), 0777, true, true);
        // }

        // Image::make($file)
        //     ->resize(800, null, function ($constraint) {
        //         $constraint->aspectRatio();
        //     })
        //     ->save(storage_path('app/public') . '/demands/' . $file_name);
        // Image::make($file)
        //     ->resize(800, null, function ($constraint) {
        //         $constraint->aspectRatio();
        //     })
        //     ->save(public_path('storage/demands/' . $file_name));

        // else

        // Storage::disk('public')->putFileAs('demands', $file, $file_name);

        $photo = Image::make($file)
            ->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })->encode($ext, 80);

        $full_path = 'uploads/demands' . '/' . $file_name;

        Storage::disk('do_spaces')->put($full_path, $photo, 'public');

        return $infos;
    }

    protected function uploadAttachment($file)
    {

        // Get image original extension
        $ext = $file->getClientOriginalExtension();
        $mimeType = $file->getClientMimeType();
        // $random = substr(md5(mt_rand()), 0, 5);
        $random = Str::uuid();
        $initial_name = $file->getClientOriginalName();

        // dd($mimeType);

        // Compose the name
        $file_name = 'attachment-' . time() . "-" . $random . '.' . $ext;

        $infos = [
            'ext' => $ext,
            'mime_type' => $mimeType,
            'file_name' => $file_name,
            'initial_name' => $initial_name,
        ];

        // Save file
        // Image::make($file)
        // ->resize(300, 300, function ($constraint) {
        //     $constraint->aspectRatio();
        // })
        // ->save(public_path('images/avatars/' . $file_name));

        // if file is image

        // if (!File::isDirectory(storage_path('app/public') . '/demands')) {
        //     File::makeDirectory(storage_path('app/public') . '/demands', 0777, true, true);
        // }
        // if (!File::isDirectory(public_path('storage/demands'))) {
        //     File::makeDirectory(public_path('storage/demands'), 0777, true, true);
        // }

        // Image::make($file)
        //     ->resize(800, null, function ($constraint) {
        //         $constraint->aspectRatio();
        //     })
        //     ->save(public_path('storage/demands/' . $file_name));

        // else

        // Storage::disk('public')->putFileAs('demands', $file, $file_name);
        $full_path = 'uploads/demands';

        Storage::disk('do_spaces')->putFileAs($full_path, $file, $file_name, 'public');

        return $infos;
    }

    public function resizeImage(Request $request)
    {
        // return response()->json(['request' => $request->all()]);

        if ($request->hasFile('the_files')) {
            $all_files = $request->file('the_files');
            foreach ($all_files as $file) {

                // Get image original extension
                $ext = $file->getClientOriginalExtension();
                $mimeType = $file->getClientMimeType();
                $random = substr(md5(mt_rand()), 0, 5);

                // dd($mimeType);

                // Compose the name
                $file_name = 'demand-file-' . time() . "-" . $random . '.' . $ext;

                $infos = [
                    'ext' => $ext,
                    'mime_type' => $mimeType,
                    'file_name' => $file_name,
                ];

                // Save file
                Image::make($file)
                    ->resize(800, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->save(public_path('storage/demands/' . $file_name));

            }

            // $quote['files'] = $quote->files;
        }

    }
}
