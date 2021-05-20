<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
    {{ Form::model($product, array('route' => array($guard_name.'.store-products.update', $product->id), 'class' => 'class-create', 'method' => 'POST', 'enctype' => 'multipart/form-data')) }}
        {{ method_field('PATCH') }}
        @include ('admin.store-products.form', ['submitButtonText' => 'Update'])
    {{ Form::close() }}
    </div>
</div>