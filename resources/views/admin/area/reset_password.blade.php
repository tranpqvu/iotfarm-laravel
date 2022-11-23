<div class="row justify-content-center">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">           
            <div class="card-body">
                <div class="col-xs-4 col-xs-offset-4">
                    <input type="hidden" id="_token" name="_token" value="{!! csrf_token() !!}" />
                    <input type="hidden" id="reset_id" name="reset_id" value="{!! $id !!}" />
                       
                    <div class="form-group">
                        <label for="reset_email" style="color:#0093ff; font-weight:bold;">Email</label>
                        <input type="text" class="form-control" id="reset_email" name="reset_email" placeholder="Email" readonly
                            maxlength="255" value="{{ $email}}"
                            placeholder="{{ $email }}" />
                    </div>

                    <div class="form-group">
                        <label for="reset_password" style="color:#0093ff; font-weight:bold;">Mật Khẩu</label>
                        <input type="text" class="form-control" id="reset_password" name="reset_password" placeholder="Mật Khẩu"
                            maxlength="255" value=""/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>