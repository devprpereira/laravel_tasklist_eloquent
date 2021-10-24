<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TasksController extends Controller
{
    private $inputError = ['inputError' => 'Required field'];

    public function list()
    {
        //Get all Task rows
        $data = Task::all();
        return view('tasks.list', ['data' => $data]);
    }

    public function add()
    {
        return view('tasks.add');
    }

    public function addAction(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string']
        ]);

        //Add task using EloquentORM
        $task = new Task();
        $task->title = $request->title;
        $task->save();

        return redirect()->route('tasks.list')
            ->with('savedSuccefully', 'Task added successfully.');
    }

    public function edit($id)
    {
        $task = $this->verifyTask($id);
        if ($task) {
            return view(
                'tasks.edit',
                [
                    'item' => $task
                ]
            );
        } else {
            return redirect()
                ->route('tasks.list')
                ->with('unableToLoad', 'Cannot update task with id #' . $id . ', please try again.');
        };
    }

    public function editAction(Request $request, $id)
    {

        $request->validate([
            'title' => ['required', 'string']
        ]);

        //Updating task with EloquentORM
        $task = self::verifyTask($id);
        $task->title = $request->title;
        $task->save();

        // This method also can be done with this: Class::find()->update();
        // but in this case we have to add 'protected $fillable in the Model and pass
        // an array with which attributes can be massive edited because Eloquent
        // doesn't know what comes before the update() and this can be a massive update
        // $task = self::verifyTask($id)
        // ->update(
        //      ['title' => $request->title]
        //  );

        $task->save();

        return redirect()
            ->route('tasks.list')
            ->with('savedSuccefully', 'Task #' . $id . ' updated succefully.');
    }

    public function delete($id)
    {
        $task = $this->verifyTask($id);

        if ($task) {
            //Finding and deleting the task with id without instantiating
            Task::find($id)->delete();

            return redirect()
                ->route('tasks.list')
                ->with('savedSuccefully', 'Task #' . $id . ' removed succefully.');
        } else {
            return redirect()
                ->route('tasks.list')
                ->with('unableToLoad', 'Cannot delete task with id #' . $id . ', please try again.');
        };
    }

    public function mark($id)
    {
        $task = $this->verifyTask($id);
        if ($task) {
            $task = Task::find($id);
            $task->done = 1 - $task->done;
            $task->save();

            return redirect()
                ->route('tasks.list')
                ->with('savedSuccefully', 'Task #' . $id . ' has "done" updated succefully.');
        } else {
            return redirect()
                ->route('tasks.list')
                ->with('unableToLoad', 'Não foi possível alterar o item de ID #' . $id);
        };
    }

    private function verifyTask($id)
    {
        $task = Task::find($id);
        return $task ?? false;
    }
}
