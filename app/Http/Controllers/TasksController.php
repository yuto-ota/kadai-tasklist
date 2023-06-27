<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Task;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::check()) { // 認証済みの場合
            // メッセージ一覧を取得
            $tasks = Task::all();
    
            // メッセージ一覧ビューでそれを表示
            return view('tasks.index', [
                'tasks' => $tasks,
            ]);
        }
        return view('dashboard');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $task = new Task;

        // メッセージ作成ビューを表示
        return view('tasks.create', [
            'task' => $task,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required',
        ]);
        
        // タスクを作成
        $task = new Task;
        $task->content = $request->content;
        $task->status = $request->status;
        $task->user_id = auth()->user()->id;
        $task->save();

        // トップページへリダイレクトさせる
        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);

        // 認証済みユーザ（閲覧者）がそのタスクの所有者である場合はタスクを表示
        if (\Auth::id() === $task->user_id) {
            // メッセージ詳細ビューでそれを表示
            return view('tasks.show', [
                'task' => $task,
            ]);
        }
        
        // トップページへリダイレクトさせる
        return redirect('/');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);
        
        // 認証済みユーザ（閲覧者）がそのタスクの所有者である場合はタスクを編集
        if (\Auth::id() === $task->user_id) {
            // メッセージ編集ビューでそれを表示
            return view('tasks.edit', [
                'task' => $task,
            ]);
        }
        
         // トップページへリダイレクトさせる
        return redirect('/');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // バリデーション
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required',
        ]);
        
        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);
        
        // 認証済みユーザ（閲覧者）がそのタスクの所有者である場合はタスクを更新
        if (\Auth::id() === $task->user_id) {
            // メッセージを更新
            $task->content = $request->content;
            $task->status = $request->status;
            $task->save();
    
            // トップページへリダイレクトさせる
            return redirect('/');
        }
        
        // トップページへリダイレクトさせる
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);
        
        // 認証済みユーザ（閲覧者）がそのタスクの所有者である場合は投稿を削除
        if (\Auth::id() === $task->user_id) {
            // メッセージを削除
            $task->delete();
    
            // トップページへリダイレクトさせる
            return redirect('/');
        }
        
        // トップページへリダイレクトさせる
        return redirect('/');
    }
}
