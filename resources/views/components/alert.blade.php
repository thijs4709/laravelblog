@props(['type'=>'success','message','model'=>'Model niet ingegeven'])
@slot('title')@endslot
    <div data-alert {{$attributes->merge(['class'=>'alert alert-' . $type .' alert-dismissible fade show'])}}>
{{--        coalescing operator
            Deze operator retrourneert de waarde van de linker operand als deze niet null is,
            anders pakt hij de rechter operand.
--}}
        <strong>{{$title ?? ucfirst($model)}}</strong>{{$message}}
        <button type="button" class="close" data-dismiss="alert" aria-label="close" title="close"><span aria-hidden="true">&times;</span></button>
    </div>
<script>
    document.addEventListener('DOMContentLoaded', function(){
        const alerts = document.querySelectorAll('[data-alert]');
        alerts.forEach(function(alert){
           window.setTimeout(function(){
               $(alert).alert('close');
           },5000);
        });
    })
</script>

