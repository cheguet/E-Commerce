<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Page;


class PageController extends Controller
{
    public function index(Request $request)
    {
        $pages = Page::latest();
         
        if(!empty($request->get('keyword'))){
            $pages = $pages->where('name','like','%'.$request->get('keyword').'%');
        }
        $pages = $pages->paginate(10);
        return view('admin.pages.list',compact('pages'));
    }

    public function create(Request $request)
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
           $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:pages',
           ]);

           if ($validator->passes())
           {
              $page = new Page();
              $page->name = $request->name;
              $page->slug = $request->slug;
              $page->status = $request->status;
              $page->content = $request->content;
              $page->save();

                $request->session()->flash('success','Page added successfully');
                return response()->json([
                    'status' => true,
                    'message' => 'Page added successfully'
                ]);
            }else{
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                 ]);
           }
    }

    public function edit(Request $request, $id)
    {
        $page = Page::find($id);

        if(empty($page)){
          return redirect()->route('pages.index');
        }
        return view('admin.pages.edit',[
            'page' => $page,
        ]);
    }

    public function update(Request $request, $id)
    {
        $page = Page::find($id);

        if(empty($page)){
          
          $request->session()->flash('error','Page not found');
  
          return redirect()->json([
            'status' => false,
            'NotFound' => true,
            'message' => 'Page Not Found'
          ]);
        }

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,'.$page->id.',id',
           ]);

           if ($validator->passes())
           {
              $page->name = $request->name;
              $page->slug = $request->slug;
              $page->status = $request->status;
              $page->content = $request->content;
              $page->save();

                $request->session()->flash('success','Page updated successfully');
                return response()->json([
                    'status' => true,
                    'message' => 'Page updated successfully'
                ]);
            }else{
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                 ]);
           }
    }

    public function destroy(Request $request, $id)
    {
        $page = Page::find($id);

        if(empty($page)){

          $request->session()->flash('error','Page not found');

          return redirect()->json([
            'status' => false,
            'NotFound' => true,
            'message' => 'Page Not Found'
          ]);
        }

        $page->delete();
        
        $request->session()->flash('success','Page deleted successfully');

        return response()->json([
            'status' => true,
            'message' => 'Page deleted successfully'
        ]);
    }
}
