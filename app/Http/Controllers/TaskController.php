<?php 

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Task;
use Illuminate\Http\Request;        
  
class TaskController extends Controller
{
  

public function  index ()
    {
        $query = task::where ('user-id',auth()->id())
          ->with('category','user');


        if (request('search')) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . request('search') . '%')
                  ->orWhere('description', 'like', '%' . request('search') . '%');
            });
            
        }


         
        if (request('status')) {
            $query->where('status', request('status'));
        }


        if (request('category_id')) {
        $query->where('category_id', request('category_id'));
        } 


        $tasks = $query->orderBy('created_at', 'desc')->paginate(8);

        $stast = [
            'total' => Task::where('user_id', auth()->id())->count(),
            'todo' => Task::where('user_id', auth()->id())->where('status', 'à faire')->count(),
            'in_progress' => Task::where('user_id', auth()->id())->where('status', 'en cours')->count(),
            'completed' => Task::where('user_id', auth()->id())->where('status', 'terminé')->count(),
        ];


        $categories = Category::where('user_id', auth()->id())->get();

        return view('tasks.index', compact('tasks', 'stats', 'categories'));
    }

    



public function create()
{
    $categories = category::where ('user_id',auth()->id())->get();

    return view('tasks.create', compact ('categories'));
}
   


public function store(Request $request)
    {
      $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:à faire,en cours,terminé',
            'due_date' => 'nullable|date',
        ]);

        Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'status' => $validated['status'],
            'due_date' => $validated['due_date'] ?? null,
            'user_id' => auth()->id(),
        ]);


        return redirect()->route('tasks.index')->with('success', 'task created with successfully!.');
    }

    public function show (task $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }
        return view('tasks.show', compact('task'));
    }
     
    public function edit(task  $task)
    {
        if ($task->user_id !== auth()->id()){
            abort(403);
        }
     $categories = category::where ('user_id',auth()->id())->get(); 
        return view('tasks.edit', compact('task','categories'));

    }
    public function update(Request $request, task $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }   
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:à faire,en cours,terminé',
            'due_date' => 'nullable|date',
        ]);

        $task->update($validated);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }
    public function destroy(task $task)
{
    if ($task->user_id !== auth()->id()) {
        abort(403);
    }

    $task->delete();

    return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
}



}















?>