<?php

namespace App\Http\Controllers\Form;

use App\Http\Controllers\Controller;
use App\Person;
use App\Phone;
use App\Profession;
use App\Professional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfessionalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $professional = DB::table('professionals')
            ->join('persons', 'professionals.id', '=', 'persons.professional_id')
            ->join('professions', 'professionals.id', '=', 'professions.professional_id')
            ->join('phones', 'professionals.id', '=', 'phones.professional_id')
            ->select('professionals.*', 'persons.*', 'professions.*','phones.*')
            ->get();


        return view('listProfessional', [
            'professionals' => $professional
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('newProfessional');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      /*  $this->validate($request, [
            'title' => 'required'
            ]);
      ]);*/ 

      $professional = new Professional();
      $professional->save();

      $person = new Person();
      $person->name = $request->name;
      $person->cpf = $request->cpf;
      $person->rg = $request->rg;
      $person->birth = $request->birth;
      $person->email = $request->email;
      $professional->person()->save($person);
      
      $profession = new Profession();
      $profession->profession = $request->profession;
      $profession->license = $request->license;
      $professional->profession()->save($profession);
      
      $phone = new Phone();
      $phone->ddd = $request->ddd;
      $phone->phone = $request->phone;
      $professional->phone()->save($phone);


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Professional  $professional
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $professional = Professional::where('id', $id)->first();
        $person = $professional->person()->first();
        $profession = $professional->profession()->first();
        $phone = $professional->phone()->first();


        return view('showProfessional', [
            'professional' => $professional,
            'person' => $person,
            'profession' => $profession,
            'phone' => $phone
        ]);
            
        /*
        if ($professional) {   
            echo "<h1>Dados do Usuário</h1>";
            echo "<p>ID: {$professional->id}</p>";
        }

        if ($person) {
            echo "<p>Nome: {$person->name}</p>";
            echo "<p>CPF: {$person->cpf}</p>";
            echo "<p>RG: {$person->rg}</p>";
            echo "<p>Data de Nascimento: {$person->birth}</p>";
            echo "<p>E-mail: {$person->email}</p>";
        }

        if ($phone) {
            echo "<p>DDD: {$phone->ddd}</p>";
            echo "<p>Telefone: {$phone->phone}</p>";
        }
        $address = $professional->address()->first();
        if ($address) {
            echo "<h1>Endereço:</h1>";
            echo "<p>Rua: {$address->address}</p>";
            echo "<p>Nº: {$address->number}</p>";
            echo "<p>Bairro: {$address->neighborhood}</p>";
            echo "<p>Cidade: {$address->city}</p>";
        }*/
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Professional  $professional
     * @return \Illuminate\Http\Response
     */
    public function edit(Professional $professional)
    {
        return view('editProfessional', [
            'professional' => $professional
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Professional  $professional
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Professional $professional)
    {

        $professional->person()->update([
            'professional_id' => $professional->id,
            'name' => $request->name,
            'cpf' => $request->cpf,
            'rg' => $request->rg,
            'email' => $request->email,
            'birth' => $request->birth
        ]);

        $professional->profession()->update([
            'professional_id' => $professional->id,
            'prof' => $request->profession,
            'license' => $request->license
        ]);

        $professional->phone()->update([
            'professional_id' => $professional->id,
            'ddd' => $request->ddd,
            'phone' => $request->phone
        ]);

        return redirect()->route('professional.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Professional  $professional
     * @return \Illuminate\Http\Response
     */
    public function destroy(Professional $professional)
    {
        $professional->delete();
        return redirect()->route('professional.index');
    }
}
