<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Image;
use App\Models\RichText;
use App\Models\Title;
use Illuminate\Http\Request;
use App\DataTables\FormDataTable;
use App\Http\Requests\StoreFormRequest;
use App\Http\Requests\UpdateFormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Str;
use Exception;

class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(FormDataTable $dataTable)
    {
        return $dataTable->render('form.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->view('form.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFormRequest $request)
    {
        $input = $request->validated();
        $input["slug"] = Str::slug($input["name"]);
        $form = Form::create($input);

        return redirect()->route('form.edit', $form)->with('success', 'Form has been created.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Form $form)
    {
        return response()->view('form.edit', compact('form'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFormRequest $request, Form $form)
    {
        $input = $request->validated();
        $input['slug'] = Str::slug($input["name"]);
        $form->update($input);

        return redirect()->route('form.index')->with('success', 'Form has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Form $form)
    {
        $form->titles()->delete();
        $form->images()->delete();
        $form->rich_texts()->delete();
        $form->delete();

        return response()->json(["status" => true, "message" => "Form has been deleted."]);
    }

    public function checkNameUnique(Request $request, string $id = null)
    {
        $name = $request->input('name');
        if (empty($id)) {
            $isUnique = Form::where('name', $name)->count() === 0;
        } else {
            $isUnique = Form::where('name', $name)->whereNot('id', $id)->count() === 0;
        }

        return $isUnique ? "true" : "false";
    }

    /**
     * @throws Exception
     */
    public function title_get(Request $request, Form $form)
    {
        $data = $form->titles()->get();

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                $html = '<div class="btn-group">';
                $html .= getEditButton('javascript:;', 'update_title_item', "data-id=\"$data->id\" data-name=\"$data->name\" data-value=\"$data->value\"");
                $html .= getDeleteButton("javascript:;", "delete_title_item", "data-id=\"$data->id\"");
                $html .= '</div>';
                return $html;
            })
            ->rawColumns(['action'])
            ->make();
    }

    public function title_add(Request $request, Form $form)
    {
        $rules = [
            'name' => ['required', Rule::unique('titles', 'name')->where(function ($query) use ($form) {
                $query->where('form_id', $form->id);
            })],
            'value' => ['required', 'string', 'max:255'],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->messages();
            $errorMessage = implode(', ', $messages->all());
            return response()->json(["status" => false, "message" => $errorMessage]);
        }

        DB::beginTransaction();
        try {
            $title = new Title();
            $title->name = $request->name;
            $title->value = $request->value;
            $form->titles()->save($title);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(["status" => false, "message" => "Something went wrong, " . $e->getMessage()]);
        }
        DB::commit();

        return response()->json(["status" => true, 'message' => "Title has been added."]);
    }

    public function title_update(Request $request, Form $form, string $detailid)
    {
        $rules = [
            'name' => ['required', Rule::unique('titles', 'name')->where(function ($query) use ($detailid, $form) {
                $query->where('form_id', $form->id)->whereNot('id', $detailid);
            })],
            'value' => ['required', 'string', 'max:255'],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->messages();
            $errorMessage = implode(', ', $messages->all());
            return response()->json(["status" => false, "message" => $errorMessage]);
        }

        DB::beginTransaction();
        try {
            $title = $form->titles()->find($detailid);
            $title->name = $request->name;
            $title->value = $request->value;
            $form->titles()->save($title);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(["status" => false, "message" => "Something went wrong, " . $e->getMessage()]);
        }
        DB::commit();

        return response()->json(["status" => true, 'message' => "Title has been updated."]);
    }

    public function title_delete(Request $request, Form $form, string $detailid)
    {
        DB::beginTransaction();
        try {
            $title = $form->titles()->find($detailid);
            $title->delete();
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(["status" => false, "message" => "Something went wrong, " . $e->getMessage()]);
        }
        DB::commit();

        return response()->json(["status" => true, 'message' => "Title has been deleted."]);
    }

    /**
     * @throws Exception
     */
    public function image_get(Request $request, Form $form)
    {
        $data = $form->images()->get();

        return datatables()->of($data)
            ->addIndexColumn()
            ->editColumn('image', function ($data) {
                if(empty($data->image)) {
                    return '-';
                }

                return '<a href="'.getStoredImage($data->image).'" data-fancybox="group"><img class="img-fluid img-general object-fit-cover" src="'.getStoredImage($data->image).'" alt="" /></a>';
            })
            ->addColumn('action', function ($data) {
                $html = '<div class="btn-group">';
                $html .= getEditButton('javascript:;', 'update_image_item', "data-id=\"$data->id\" data-name=\"$data->name\" data-value=\"$data->value\"");
                $html .= getDeleteButton("javascript:;", "delete_image_item", "data-id=\"$data->id\"");
                $html .= '</div>';
                return $html;
            })
            ->rawColumns(['image', 'action'])
            ->make();
    }

    public function image_add(Request $request, Form $form)
    {
        $rules = [
            'name' => ['required', Rule::unique('images', 'name')->where(function ($query) use ($form) {
                $query->where('form_id', $form->id);
            })],
            'image' => ['required', 'image'],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->messages();
            $errorMessage = implode(', ', $messages->all());
            return response()->json(["status" => false, "message" => $errorMessage]);
        }

        DB::beginTransaction();
        try {
            $image = new Image();
            $image->name = $request->name;
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $image->image = $request->file('image')->store('public/forms');
            }
            $form->images()->save($image);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(["status" => false, "message" => "Something went wrong, " . $e->getMessage()]);
        }
        DB::commit();

        return response()->json(["status" => true, 'message' => "Image has been added."]);
    }

    public function image_update(Request $request, Form $form, string $detailid)
    {
        $rules = [
            'name' => ['required', Rule::unique('images', 'name')->where(function ($query) use ($detailid, $form) {
                $query->where('form_id', $form->id)->whereNot('id', $detailid);
            })],
            'image' => ['nullable', 'image'],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->messages();
            $errorMessage = implode(', ', $messages->all());
            return response()->json(["status" => false, "message" => $errorMessage]);
        }

        DB::beginTransaction();
        try {
            $image = $form->images()->find($detailid);
            $image->name = $request->name;
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                deleteStoredImage($image->image);
                $image->image = $request->file('image')->store('public/forms');
            }
            $form->images()->save($image);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(["status" => false, "message" => "Something went wrong, " . $e->getMessage()]);
        }
        DB::commit();

        return response()->json(["status" => true, 'message' => "Image has been updated."]);
    }

    public function image_delete(Request $request, Form $form, string $detailid)
    {
        DB::beginTransaction();
        try {
            $image = $form->images()->find($detailid);
            deleteStoredImage($image->image);
            $image->delete();
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(["status" => false, "message" => "Something went wrong, " . $e->getMessage()]);
        }
        DB::commit();

        return response()->json(["status" => true, 'message' => "Image has been deleted."]);
    }

    public function rich_text_get(Request $request, Form $form)
    {
        $data = $form->rich_texts()->get();

        return datatables()->of($data)
            ->addIndexColumn()
            ->editColumn('value', function ($data) {
                return Str::limit(strip_tags($data->value), 30);
            })
            ->addColumn('action', function ($data) {
                $html = '<div class="btn-group">';
                $html .= getEditButton('javascript:;', 'update_rich_text_item', "data-id=\"$data->id\" data-name=\"$data->name\" data-value=\"$data->value\"");
                $html .= getDeleteButton("javascript:;", "delete_rich_text_item", "data-id=\"$data->id\"");
                $html .= '</div>';
                return $html;
            })
            ->rawColumns(['action'])
            ->make();
    }

    public function rich_text_add(Request $request, Form $form)
    {
        $rules = [
            'name' => ['required', Rule::unique('rich_texts', 'name')->where(function ($query) use ($form) {
                $query->where('form_id', $form->id);
            })],
            'value' => ['required'],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->messages();
            $errorMessage = implode(', ', $messages->all());
            return response()->json(["status" => false, "message" => $errorMessage]);
        }

        DB::beginTransaction();
        try {
            $rich_text = new RichText();
            $rich_text->name = $request->name;
            $rich_text->value = $request->value;
            $form->rich_texts()->save($rich_text);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(["status" => false, "message" => "Something went wrong, " . $e->getMessage()]);
        }
        DB::commit();

        return response()->json(["status" => true, 'message' => "Rich Text has been added."]);
    }

    public function rich_text_update(Request $request, Form $form, string $detailid)
    {
        $rules = [
            'name' => ['required', Rule::unique('rich_texts', 'name')->where(function ($query) use ($detailid, $form) {
                $query->where('form_id', $form->id)->whereNot('id', $detailid);
            })],
            'value' => ['required'],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->messages();
            $errorMessage = implode(', ', $messages->all());
            return response()->json(["status" => false, "message" => $errorMessage]);
        }

        DB::beginTransaction();
        try {
            $rich_text = $form->rich_texts()->find($detailid);
            $rich_text->name = $request->name;
            $rich_text->value = $request->value;
            $form->rich_texts()->save($rich_text);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(["status" => false, "message" => "Something went wrong, " . $e->getMessage()]);
        }
        DB::commit();

        return response()->json(["status" => true, 'message' => "Rich Text has been updated."]);
    }

    public function rich_text_delete(Request $request, Form $form, string $detailid)
    {
        DB::beginTransaction();
        try {
            $rich_text = $form->rich_texts()->find($detailid);
            $rich_text->delete();
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(["status" => false, "message" => "Something went wrong, " . $e->getMessage()]);
        }
        DB::commit();

        return response()->json(["status" => true, 'message' => "Rich Text has been deleted."]);
    }
}
