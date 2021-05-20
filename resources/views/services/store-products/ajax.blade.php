<div class="modal-dialog modal-dialog-centered" role="document">
  <div class="modal-content">
    {{ Form::model($product, array('route' => array($guard_name.'.service-store-products.update', $product->id),'class' => 'class-create', 'method' => 'POST', 'enctype' => 'multipart/form-data')) }}
        <input type="hidden" name="id" value="{{ $product->id}}" />
        <?php 
          $sid = 1;
          if($product->store->service_type == "service_type_2"){
            $sid = 2;
          }
          else if($product->store->service_type == "service_type_3"){
            $sid = 3;
          }
        ?>
        <input type="hidden" name="sid" value="{{ $sid }}" />
        {{ method_field('PATCH') }}
        @include ('services.store-products.form', ['submitButtonText' => 'Update'])
    {{ Form::close() }}
  </div>
</div>