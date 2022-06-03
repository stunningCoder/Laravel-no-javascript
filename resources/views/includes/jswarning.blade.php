@if(config('marketplace.js_warning'))
    <div class="mt-3">
        <div id="jswarning"></div>
    </div>
    <script>
        let warningText = 'Hey Pal... ManCave Market does not need Javascript to function, once you disable JS this message will disappear!'
        let jsWarning = document.getElementById('jswarning');
        let alert = document.createElement('div');
        let span = document.createElement('span');
        alert.classList.add('alert');
        alert.classList.add('alert-danger');
        span.innerText = warningText;
        alert.appendChild(span);
        jsWarning.appendChild(alert);
    </script>
@endif

