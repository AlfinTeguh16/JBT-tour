@props([
  'action' => '#',
  'method' => 'POST',
  'id' => null,
  'class' => null,
  'files' => false,
])

@php
  $formMethod = strtoupper($method);
  $spoofMethod = null;
  if (!in_array($formMethod, ['GET','POST'])) {
      $spoofMethod = $formMethod;
      $formMethod = 'POST';
  }
@endphp

<form action="{{ $action }}"
      method="{{ strtolower($formMethod) }}"
      @if($id) id="{{ $id }}" @endif
      @if($files) enctype="multipart/form-data" @endif
      {{ $attributes->merge(['class' => $class]) }}>
    @csrf
    @if($spoofMethod)
      @method($spoofMethod)
    @endif

    {{ $slot }}
</form>
