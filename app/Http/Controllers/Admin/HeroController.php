<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hero;
use Illuminate\Http\Request;

class HeroController extends Controller
{
    public function index()
    {
        $heroes = Hero::latest()->get();
        return view('admin.hero.index', compact('heroes'));
    }

    public function create()
    {
        return view('admin.hero.create');
    }

    public function store(Request $request)
    {
        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('hero', 'public');
        }

        Hero::create($data);

        return redirect()->route('admin.hero.index')->with('success','Hero Added');
    }

    public function edit($id)
    {
        $hero = Hero::findOrFail($id);
        return view('admin.hero.edit', compact('hero'));
    }

    public function update(Request $request, $id)
    {
        $hero = Hero::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('hero', 'public');
        }

        $hero->update($data);

        return redirect()->route('admin.hero.index')->with('success','Hero Updated');
    }

    public function destroy($id)
    {
        Hero::findOrFail($id)->delete();
        return back()->with('success','Deleted');
    }
}