<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\TemplateModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TemplateController extends Controller
{
    public function getAllData()
    {
        $data = TemplateModel::all();
        return response()->json([
            'code' => 200,
            'message' => 'success get template',
            'data' => $data
        ]);
    }

    public function createData(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'image_template' => 'required'
        ]);

        if ($validation->fails()) {
            return response()->json([
                'code' => 401,
                'message' => 'check your validation',
                'errors' => $validation->errors()
            ]);
        }

        try {
            $data = new TemplateModel;
            $data->uuid = Uuid::uuid4()->toString();
            if ($request->hasFile('image_template')) {
                $file = $request->file('image_template');
                $extention = $file->getClientOriginalExtension();
                $filename = 'TEMPLATE-' . Str::random(15) . '.' . $extention;
                Storage::makeDirectory('uploads/template/');
                $file->move(public_path('uploads/template/'), $filename);
                $data->image_template = $filename;
            }
            $data->save();
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 402,
                'message' => 'failed'
            ]);
        }

        return response()->json([
            'message' => 'success ',
            'data' => $data
        ]);
    }
}
