<div class="card card-outline card-info">
    <div class="overlay" style="display: none;">
        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
    </div>
    <div class="card-body pad">
        <div class="row">
            {{-- <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('unit price', 'Unit Price') }}
                    {{ Form::text('unit_price', null, array('required'=>'', 'class' => 'form-control'.($errors->has('unit_price') ? ' is-invalid' : '' ))) }}
                    {!! $errors->first('unit_price','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div> --}}
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('ask price', 'Ask Price') }}
                    {{ Form::text('ask_price', null, array('required'=>'', 'class' => 'form-control'.($errors->has('ask_price') ? ' is-invalid' : '' ))) }}
                    {!! $errors->first('ask_price','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('ask price', 'Orginal Price') }}
                    {{ Form::text('unit_price', null, array('disabled', 'class' => 'form-control'.($errors->has('ask_price') ? ' is-invalid' : '' ))) }}
                    {!! $errors->first('unit_price','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
            @if($guard_name != 'admin')
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('stock', 'Stock') }}
                    {{ Form::text('stock', null, array('required'=>'','class' => 'form-control'.($errors->has('stock') ? ' is-invalid' : '' ))) }}
                    {!! $errors->first('stock','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('quantity-per-person', 'Quantity/Person') }}
                    {{ Form::text('quantity_per_person', null, array('required'=>'','class' => 'form-control'.($errors->has('quantity_per_person') ? ' is-invalid' : '' ))) }}
                    {!! $errors->first('quantity_per_person','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
                
            </div>
        
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('quantity-per-person', 'Out of Stock') }}<br>
                    <input class='form-control' type="checkbox" name="out_of_stock" value="1" id="custdomSwitch3" @if (!empty($product) && $product->out_of_stock == 1) checked @endif data-bootstrap-switch>
                 </div>
            </div>
            @endif
        </div>
    </div>
    <div class="card-footer">
        @if($guard_name == 'admin')
            <button type="submit" name="reject" class="btn btn-warning" value="1">Reject</button>
            <a href="{{ route('admin.store-products.index') }}" class="btn btn-default">Cancel</a>
            <button type="submit" name="approve" class="btn btn-info float-right" value="1">Approve</button>
        </form>
        @else
            <a href="{{ route('store.store-products.index') }}" class="btn btn-default">Cancel</a>
            {{ Form::submit(isset($submitButtonText) ? $submitButtonText : 'Submit', array('class' => 'btn btn-info float-right')) }}
        @endif
    </div>
</div>
