<ul>
    @foreach($categories as $k=>$cat)
        @php
            $expand = false;
            $checked = false;

            if(array_key_exists($cat->id,$categoryState)){
                $expand = $categoryState[$cat->id]['expand'];
                $checked=$categoryState[$cat->id]['checked'];
            }
        @endphp
        <li>
            <div class="list-group-item {{($checked?"active":'')}}">
                @if($cat->children->isNotEmpty())
                    <input type="checkbox" id="node{{$level}}{{"-".$k}}" {{$expand?'checked="checked"':''}} />
                    <label for="node{{$level}}{{"-".$k}}"></label>
                @endif
                <label>
                    <input type="checkbox" @if($cat->children->isEmpty())id="node{{$level}}{{"-".$k}}" @endif {{$checked?'checked="checked"':''}} />
                    @if($cat->children->isNotEmpty())
                        <a href="{{$checked?route('category.deselect',['categoryId'=>$cat->id]):route('category.select',['categoryId'=>$cat->id])}}">
                            <span></span>
                        </a>
                    @else
                        <a href="{{$checked?route('category.deselect',['categoryId'=>$cat->id]):route('category.select',['categoryId'=>$cat->id])}}">
                            <span></span>
                        </a>
                    @endif
                </label>
                <a href="{{ route('category.show', $cat) }}" class="{{($checked?'active':'')}}">{{$cat->name}}
                    <em>{{ $cat->num_products }}</em>
                </a>
                @if($cat->children->isNotEmpty())
                    @include('includes.subcategories', ['categories' => $cat->children,'level'=>"-".$k])
                @endif
            </div>
        </li>
    @endforeach
</ul>
