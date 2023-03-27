@props(['model','alt'=>'cms syntra','sizeimgnf','width','height'])

<img class="img-thumbnail" width="62" height="62"
     src="{{$model->photo ? asset($model->photo->file) : "http://via.placeholder.com/$sizeimgnf"}}"
     alt="{{$alt}}">
