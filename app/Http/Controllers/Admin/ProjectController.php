<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Technology;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


// helper per le stringhe
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::all();

        return view('admin.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = Type::all();

        $technologies = Technology::all();

        return view('admin.projects.create', compact('types', 'technologies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $formData = $request->all();

        // dd($formData);

        // funzione di validazione
        $this->validation($formData);

        $project = new Project();

        $project->fill($formData);

        $project->slug = Str::slug($project->title, '-');

        // lo sposto sopra il successivo if, perchè solo quando effetuiamo il salvataggio del dato della riga del database viene generato l'id
        $project->save();

        // inserisco le technologie relative ai progetti nella tabella ponte
        if (array_key_exists('technologies', $formData)) {

            // il metdodo attach ci permette di inserire in automatico nella tabella ponte i collegamemti, riga per riga, con le technologie passatagli tramite un array
            $project->technologies()->attach($formData['technologies']);
        }

        return redirect()->route('admin.projects.show', $project);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {

        return view('admin/projects/show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        $types = Type::all();

        $technologies = Technology::all();

        return view('admin.projects.edit', compact('project', 'types', 'technologies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        // dd($request);

        $formData = $request->all();

        $this->validation($formData);

        // metodo 1
        // $project->slug = Str::slug($formData['title'], '-');

        // metodo 2
        $formData['slug'] = Str::slug($formData['title'], '-');

        $project->update($formData);

        if (array_key_exists('technologies', $formData)) {

            // la funzione sync permette di sincronizzare i tag selezionati nel form, con quelli presenti nella tabella ponte, in modo tale da non creare dei dublicati
            $project->technologies()->sync($formData['technologies']);

            // devo comprendere anche il caso in cui non seleziono nessun tag(non entra nell'if) quindi devo eliminare i suoi riferimenti dalla tabella ponte
        } else {

            // detach è il metodo che fa ciò che viene descritto nel commento sopra
            $project->technologies()->detach();
        }

        return redirect()->route('admin.projects.show', $project);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('admin.projects.index');
    }

    private function validation($formData)
    {


        $validator = Validator::make($formData, [

            'title' => 'required|max:255|min:3',
            'content' => 'required',
            // type_id può essere nullo e deve esistere nella tabella 'types', 'id
            'type_id' => 'nullable|exists:types,id',
            'technologies' => 'exists:technologies,id',

        ], [

            'title.required' => 'Devi inserire il titolo',
            'title.max' => 'Il titolo non deve essere più lungo di 100 caratteri',
            'title.min' => 'Il titolo deve avere minimo 3 caratteri',
            'content.required' => 'Devi inserire un contenuto',
            'type_id.exists' => 'La tipologia deve essere presente nella lista',
            'technologies.exists' => 'La tecnologia deve essere presente nella lista'

        ])->validate();

        return $validator;
    }
}
