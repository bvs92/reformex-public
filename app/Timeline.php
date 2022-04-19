<?php

namespace App;

use App\User;
use App\Quote;
use App\Demand;
use App\Review;
use App\Prospect;
use App\Professional;
use App\ClientMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Timeline extends Model
{
    protected $fillable = ['demand_id', 'user_id', 'professional_id', 'uuid'];

    // Relationships
    public function demand()
    {
        return $this->belongsTo(Demand::class);
    }

    public function user() // client
    {
        return $this->belongsTo(User::class);
    }

    public function client_messages()
    {
        return $this->hasMany(ClientMessage::class);
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    public function professional() // client
    {
        return $this->belongsTo(Professional::class);
    }

    public function prospects() // must have many to allow Client to send multiple PROPOSALS to pro, only after responding the existing one. 
    {
        // return $this->hasOne(Prospect::class);
        return $this->hasMany(Prospect::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }




    // Helpers

    public function belongsToMeClient()
    {
        return $this->user_id == auth()->user()->id ? true : false;
    }

    public function getStatus()
    {
        return $this->status;
    }


    public function isActive()
    {
        return $this->status == '0' ? true : false;
    }

    public function isCompleted()
    {
        return $this->status == '1' ? true : false;
    }

    public function hasReview()
    {
        return $this->review ? true : false;
    }


    public function hasUUID()
    {
        return $this->uuid != NULL ? true : false;
    }

    public function getUUID()
    {
        return $this->uuid;
    }


    public function getDisponibleId()
    {
        return $this->hasUUID() ? $this->getUUID() : $this->id;
    }


    public function deleteFromPro()
    {
        return $this->delete_pro;
    }

    public function deleteFromClient()
    {
        return $this->delete_client;
    }




    // delete
    public function delete_from_client()
    {
        
        if($this->client_messages && $this->client_messages()->count() > 0){
            foreach($this->client_messages as $client_message){
                if($client_message->files && $client_message->files->count() > 0){
            
                    foreach($client_message->files as $theFile){
                        // $files->push($theFile);  
                        // $pathToFile = public_path() . '/storage\/quotes\/' . $theFile->name;
                        // if(file_exists($pathToFile)){
                        //     unlink($pathToFile);
                        // }
        
                        $theFile->delete();
                    }
                }

                $client_message->delete();
            }
        }


        if($this->deleteFromPro()){

            // verifica si sterge winners
            $winner = \App\Winner::where('demand_id', $this->demand_id)->where('professional_id', $this->professional_id)->first();
            if($winner && $winner->count() > 0){
                $winner->delete();
            }
            
            // verifica si sterge prospects
            if($this->prospects && $this->prospects()->count() > 0){
                $this->prospects()->delete();
            }

            // verifica si sterge demand_professional (cumparator cerere)
            DB::table('demand_professional')->where('demand_id', $this->demand_id)->where('professional_id', $this->professional_id)->delete();
            $this->delete();


        } else {
            $this->delete_client = true;
            $this->save();
        }


    }
}
