<?php

namespace App\Http\Controllers;

use App\Credit;
use App\Professional;
use App\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class ProfessionalsController extends Controller
{

    public function activate(Request $request)
    {
        // $validated = $request->validate([
        //     'name' => 'sometimes|max:255'
        // ]);

        if (auth()->user()->isPro()) {
            return redirect()->route('user.profile.settings')->with('info', 'Aveti deja un cont de firma activ.');
        }

        if (!Professional::create([
            'user_id' => auth()->user()->id,
        ])) {
            return redirect()->back()->with('error', 'Am intampinat erori. Va rugam incercati mai tarziu.');
        }

        // Creare profil Credit
        if (!auth()->user()->credit) {
            Credit::create([
                'user_id' => auth()->user()->id,
                'amount' => 0,
            ]);
        }

        // Adauga rol de professional
        $role = Role::where('name', 'professional')->first();
        auth()->user()->syncRoles($role);

        return redirect()->route('user.profile.settings')->with('success', 'Contul de firma a fost activat.');
    }

    // public function activateAsAdmin(Request $request, $id)
    // {
    //     $user = User::findOrFail($id);

    //     if($user->isPro())
    //         return redirect()->back()->with('info', 'Exista deja un cont de profesionist activat.');

    //     if(!Professional::create([
    //         'user_id' => $user->id,
    //         'name' => $user->getName() . ' SRL'
    //     ])){
    //         return redirect()->back()->with('error', 'Am intampinat erori. Va rugam incercati mai tarziu.');
    //     }

    //     Credit::create([
    //         'user_id' => $user->id,
    //         'amount' => 0
    //     ]);

    //     return redirect()->route('users.index')->with('success', 'Contul de profesionist a fost activat.');
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id)
    // {
    //     $validated = $request->validate([
    //         'pro_name' => 'required|min:2|max:255'
    //     ]);

    //     $pro = auth()->user()->professional;

    //     // check if i can change my info
    //     $this->authorize('update', $pro);

    //     $pro->name = $validated['pro_name'];
    //     // dd($pro->name);

    //     if(!$pro->save()){
    //         return redirect()->back()->with('error', 'Am intampinat erori. Incercati mai tarziu.');
    //     }

    //     return redirect()->route('user.profile')->with('success', 'Operatie efectuata cu succes.');
    // }

    public function updatePro(Request $request)
    {

        // dd($request->input('the-city'));

        $validated = $request->validate([
            'range' => 'required|numeric|min:1',
            'city' => 'required',
        ]);

        $pro = auth()->user()->professional;

        $pro->range = $validated['range'] * 1000; // transformare in M
        $pro->city = $request->input('the-city');
        $pro->administrative = $request->input('administrative');
        $pro->postal_code = $request->input('postal_code');
        $pro->lat = $request->input('lat');
        $pro->lng = $request->input('lng');

        // dd($pro->name);

        if (!$pro->save()) {
            return redirect()->back()->with('error', 'Am intampinat erori. Incercati mai tarziu.');
        }

        return redirect()->route('user.profile')->with('success', 'Operatie efectuata cu succes.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function updateCategories(Request $request)
    {
        $validated = $request->validate([
            'categories' => 'required|exists:categories,id',
        ]);

        // dd($validated['categories']);
        if (!auth()->user()->professional->categories()->sync($validated['categories'])) {
            return redirect()->back()->with('error', 'Am intampinat erori. Incercati mai tarziu.');
        }

        return redirect()->route('user.profile')->with('success', 'Operatie efectuata cu succes.');

        // dd($request->all());
    }

    public function eliminateCategories()
    {
        // dd($validated['categories']);
        if (!auth()->user()->professional->categories()->detach()) {
            return redirect()->back()->with('error', 'Am intampinat erori. Incercati mai tarziu.');
        }

        return redirect()->route('user.profile')->with('success', 'Operatie efectuata cu succes.');
    }

    //

}
