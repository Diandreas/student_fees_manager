<?php
namespace App\Http\Controllers;

use App\Models\Field;
use App\Models\Campus;
use Illuminate\Http\Request;

class FieldController extends Controller
{
    public function index()
    {
        $fields = Field::with('campus')->paginate(10);
        return view('fields.index', compact('fields'));
    }

    public function create()
    {
        $campuses = Campus::all();
        return view('fields.create', compact('campuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'campus_id' => 'required|exists:campuses,id',
            'fees' => 'nullable|numeric|min:0'
        ]);

        Field::create($validated);
        return redirect()->route('fields.index')
            ->with('success', 'Field created successfully');
    }

    public function edit(Field $field)
    {
        $campuses = Campus::all();
        return view('fields.edit', compact('field', 'campuses'));
    }

    public function update(Request $request, Field $field)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'campus_id' => 'required|exists:campuses,id',
            'fees' => 'nullable|numeric|min:0'
        ]);

        $field->update($validated);
        return redirect()->route('fields.index')
            ->with('success', 'Field updated successfully');
    }

    public function destroy(Field $field)
    {
        $field->delete();
        return redirect()->route('fields.index')
            ->with('success', 'Field deleted successfully');
    }
}
