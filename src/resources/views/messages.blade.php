<div class="form-messages">
    @if($form->getErrors() !== null)
        @foreach($form->getErrors() as $error)
            <div class="alert alert-danger">{{ $error->getMessage() }}</div>
        @endforeach
    @endif

    @if($form->shouldShowSuccessMessage() && $msg = $form->getSuccessMessage())
        <div class="alert alert-success">{{ $msg }}</div>
    @endif
</div>
