@extends('master.admin')
<link rel="stylesheet" href="{{asset('richtexteditor/richtexteditor/rte_theme_default.css')}}" />
<script type="text/javascript" src="{{asset("/richtexteditor/richtexteditor/rte.js")}}"></script>
<script type="text/javascript" src="{{asset('richtexteditor/richtexteditor/plugins/all_plugins.js')}}"></script>

<style>
    .text-center{
        text-align:center;
    }
</style>
@section('admin-content')

    @include('includes.flash.success')
    @include('includes.flash.error')
    <h3 class="mb-5">Create Post Announcement</h3>

    <div class="row">
        <form action="{{route('admin.announcement.new')}}" method="post">
            @csrf
            <input name="htmlcode" id="inp_htmlcode" type="hidden" />
            <input name="post_id" value="{{$data[0]->id}}" type="hidden" />


            <div id="div_editor1" class="richtexteditor" style="width: 836px;margin:0 auto;">
                {!! $data[0]->body !!}
            </div>

            <script>
                var editor1 = new RichTextEditor(document.getElementById("div_editor1"));
                editor1.attachEvent("change", function () {
                    document.getElementById("inp_htmlcode").value = editor1.getHTMLCode();
                });
            </script>

            <div style="margin:0 auto;padding:24px;">
                <button class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>


@stop
