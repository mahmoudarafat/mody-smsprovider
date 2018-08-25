<?php

namespace mody\smsprovider\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use mody\smsprovider\Models\Template;

class SMSProviderTemplatesController extends Controller
{

    public function userTemps()
    {
        try {
            $templates = Template::where('user_id', auth()->user()->id)
//                ->where('status', true)
                ->paginate(20);
            return $templates;
        } catch (\Exception $e) {
            return collect();
        }
    }

    public function groupTemps()
    {
        $trytwo = Template::where('group_id', session('group_id'))
//            ->where('status', true)
            ->paginate(20);
        return $trytwo;
    }

    public function userTrashTemps()
    {
        try {
            $templates = Template::onlyTrashed()
//                ->where('status', true)
                ->where('user_id', auth()->user()->id)
                ->paginate(20);
            return $templates;
        } catch (\Exception $e) {
            return collect();
        }
    }

    public function groupTrashTemps()
    {
        $trytwo = Template::onlyTrashed()
//            ->where('status', true)
            ->where('group_id', session('group_id'))->paginate(20);
        return $trytwo;
    }

    public function storeArrayTemplates($array)
    {
        foreach ($array as $value) {
            $temp = new Template();
            $temp->message_type = $value['title'];
            $temp->message = $value['message'];
            $temp->user_id = auth()->user() ? auth()->user()->id : 0;
            $temp->group_id = session('group_id') ?? 0;
            $temp->status = 1;
            $temp->save();
        }
    }

    public function userTempsView()
    {
        try {
            $title = trans('smsprovider::smsgateway.user_templates_title');
            $templates = $this->userTemps();
            return view('smsprovider::auth-temps', compact('templates', 'title'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function groupTempsView()
    {
        try {
            $title = trans('smsprovider::smsgateway.group_templates_title');
            $templates = $this->groupTemps();
            return view('smsprovider::group-temps', compact('templates', 'title'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function userTrashTempsView()
    {
        try {
            $title = trans('smsprovider::smsgateway.user_trashed_templates_title');
            $templates = $this->userTrashTemps();
            return view('smsprovider::auth-temps', compact('templates', 'title'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function groupTrashTempsView()
    {
        try {
            $title = trans('smsprovider::smsgateway.group_trashed_templates_title');
            $templates = $this->groupTrashTemps();
            return view('smsprovider::group-temps', compact('templates', 'title'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function changeTempStat($template_id)
    {
        $temp = Template::find($template_id);
        if ($temp) {
            $temp->status = !$temp->status;
            $temp->save();
            return true;
        } else {
            return false;
        }
    }

    public function recoverATemplate($template_id)
    {
        $temp = Template::onlyTrashed()->find($template_id);
        if ($temp) {
            $temp->restore();
            return true;
        } else {
            return false;
        }
    }

    public function trashATemplate($template_id)
    {
        $temp = Template::find($template_id);
        if ($temp) {
            $temp->delete();
            return true;
        } else {
            return false;
        }
    }

    public function removeATemplate($template_id)
    {
        $temp = Template::find($template_id);
        if ($temp) {
            $temp->forceDelete();
            return true;
        } else {
            return false;
        }
    }

    public function editTemplate($template_id)
    {
        $template = Template::findOrFail($template_id);
        return view('smsprovider::edit-template', compact('template'));
    }

    public function updateTemplate(Request $request)
    {
        $rules = [
          'title' => 'required',
          'message' => 'required',
        ];

        $messages = [
          'title.required' => trans('smsprovider.smsgateway.title_required'),
          'message.required' => trans('smsprovider.smsgateway.message_required'),
        ];

        $this->validate($request, $rules, $messages);

        $template = Template::findOrFail($request->template_id);
        $template->message_type = $request->title;
        $template->message = $request->message;
        $template->status = $request->status ? 1 : 0;
        $template->save();

        return redirect()->route('smsprovider.providers.user-templates')->with(['success' => trans('smsprovider.smsgateway.saved')]);
    }
}