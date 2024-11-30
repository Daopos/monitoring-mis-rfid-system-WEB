<?php

namespace App\Http\Controllers;

use App\Models\Block;
use Illuminate\Http\Request;

class BlockController extends Controller
{
    // Display list of blocks
    public function index()
    {
        $blocks = Block::all();
        return view('admin.blockslist', compact('blocks'));
    }

    // Store a new block
    public function store(Request $request)
    {
        $request->validate([
            'block' => 'required|string',
            'number' => 'required|string',
            'lot' => 'required|string',
            'details' => 'nullable|string',
        ]);

        Block::create($request->all());

        return redirect()->route('blocks.index')->with('success', 'Block created successfully');
    }

    // Update the block
    public function update(Request $request, Block $block)
    {
        $request->validate([
            'block' => 'required|string',
            'number' => 'required|string',
            'lot' => 'required|string',
            'details' => 'nullable|string',
        ]);

        $block->update($request->all());

        return redirect()->route('blocks.index')->with('success', 'Block updated successfully');
    }

    // Delete a block
    public function destroy(Block $block)
    {
        $block->delete();

        return redirect()->route('blocks.index')->with('success', 'Block deleted successfully');
    }
}
