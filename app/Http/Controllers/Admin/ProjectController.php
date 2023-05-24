<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
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

        return view('admin.projects.create', compact('types'));
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

        $project->save();

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

        return view('admin.projects.edit', compact('project', 'types'));
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
            'type_id' => 'nullable|exists:types,id'

        ], [

            'title.required' => 'Devi inserire il titolo',
            'title.max' => 'Il titolo non deve essere più lungo di 100 caratteri',
            'title.min' => 'Il titolo deve avere minimo 3 caratteri',
            'content.required' => 'Devi inserire un contenuto',
            'type_id.exists' => 'La tipologia deve essere presente nella lista'

        ])->validate();

        return $validator;
    }
}
