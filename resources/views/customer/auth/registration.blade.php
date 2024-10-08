<!doctype html>
<html lang="en">

<head>
    <title>{{__('custom.registration')}} | {{ config('site_title') ?? config('app.name') }}</title>
    @include('customer.auth.head')
</head>

<body class="body_color">
<section class="ic_main_form_area">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12 col-md-12 m-auto ml-lg-auto">
                <div class="ic_main_form_inner w-100">
                    <div class="form_box">
                        <div class="col-lg-12">
                            @if(config('site_logo'))
                                <img class="ic-login-img img-fluid" src="{{ config('site_logo') }}" alt="logo">
                            @else
                                <img class="img-fluid ic-login-img" src="{{ config('site_logo') ?? static_asset('admin/images/logo.png') }}" alt="imgs">

                            @endif

                            <h2>{{__('custom.registration')}}</h2>
                        </div>

                        @if(session()->has('loginFail'))
                            <p class="alert alert-danger text-center">
                                {{ session()->get('loginFail') }}
                            </p>
                        @endif


                        <form class="mt-4 ic_main_form_inner text-left w-auto shadow-none p-0" action="{{ route('customer.auth.store.customer') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">

                                <div class="form-group col-sm-6">
                                    <label for="">{{__('custom.first_name')}} <span class="error">*</span></label>
                                    <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}"
                                           required>
                                    @error('first_name')
                                    <p class="error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="form-group col-sm-6">
                                    <label for="">{{__('custom.last_name')}} <span class="error">*</span></label>
                                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}"
                                           required>
                                    @error('last_name')
                                    <p class="error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="form-group col-sm-6">
                                    <label for="">{{__('custom.email')}} <span class="error">*</span></label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                                    @error('email')
                                    <p class="error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="form-group col-sm-6">
                                    <label for="">{{__('custom.phone')}} <span class="error">*</span></label>
                                    <input type="text" name="phone" class="form-control phone"
                                           value="{{ old('phone') ?? '+1' }}" required>
                                    @error('phone')
                                    <p class="error">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>{{ __('custom.password') }} <span class="error">*</span></label>
                                    <input type="password" name="password" class="form-control" required
                                           placeholder="Enter password">

                                    @error('password')
                                    <p class="error">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group col-sm-6">
                                    <label>{{ __('custom.confirm_password') }} <span class="error">*</span></label>
                                    <input type="password" name="password_confirmation" class="form-control" required
                                           placeholder="Type password again">

                                    @error('password_confirmation')
                                    <p class="error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="form-group col-sm-6">
                                    <label for="">{{__('custom.company')}} </label>
                                    <input type="text" name="company" class="form-control" value="{{ old('company') }}">
                                    @error('company')
                                    <p class="error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="form-group col-sm-6">
                                    <label for="">{{__('custom.designation')}}</label>
                                    <input type="text" name="designation" class="form-control" value="{{ old('designation') }}">
                                    @error('designation')
                                    <p class="error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-sm-12">
                                    <label for="" class="text-muted">{{ __('custom.address') }}</label>
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            <label for="">{{__('custom.address_line_1')}}</label>
                                            <input type="text" name="address_line_1" class="form-control"
                                                   value="{{ old('address_line_1') }}">
                                            @error('address_line_1')
                                            <p class="error">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="form-group col-sm-6">
                                            <label for="">{{__('custom.address_line_2')}}</label>
                                            <input type="text" name="address_line_2" class="form-control"
                                                   value="{{ old('address_line_2') }}">
                                            @error('address_line_2')
                                            <p class="error">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group ic-select-gray-bg">
                                                <label for="#">{{ __('custom.country') }} </label>
                                                <select id="country" name="country" class="form-control select2" >
                                                    <option value="">{{ __('custom.select') }} {{ __('custom.country') }}
                                                    </option>
                                                    @foreach($countries as $country)
                                                        <option value="{{ $country->id }}">
                                                            {{ $country->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('country')
                                                <p class="error">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group ic-select-gray-bg">
                                                <label for="#">{{ __('custom.state') }} </label>
                                                <select id="state" name="state" class="form-control select2" >
                                                    <option value="">{{ __('custom.select') }} {{ __('custom.state') }}</option>
                                                </select>

                                                @error('state')
                                                <p class="error">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group ic-select-gray-bg">
                                                <label for="#">{{ __('custom.city') }}</label>
                                                <select id="city" name="city" class="form-control select2">
                                                    <option value="">{{ __('custom.select') }} {{ __('custom.city') }}</option>
                                                </select>

                                                @error('city')
                                                <p class="error">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group col-sm-6">
                                            <label for="">{{__('custom.zipcode')}}</label>
                                            <input type="number" name="zipcode" class="form-control"
                                                   value="{{ old('zipcode') }}"
                                                   maxlength="12">
                                            @error('zipcode')
                                            <p class="error">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="form-group col-sm-12">
                                            <label for="short_address">{{ __t('short_address') }} <small>({{ __t('short_address_note') }})</small></label>
                                            <textarea name="short_address" class="form-control" id="short_address"
                                                      placeholder="{{ __t('short_address') }}">{{ old('short_address') }}</textarea>
                                            @error('short_address')
                                            <p class="error">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <label for="" class="text-muted">{{ __('custom.billing_address') }}</label>
                                    <div class="row">
                                        <div class="form-group col-sm-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1"
                                                       id="billingSameAsAddress" name="billing_same">
                                                <label class="form-check-label checkbox-label" for="billingSameAsAddress">
                                                    {{ __('custom.billing_address_same') }}
                                                </label>
                                            </div>

                                            @error('is_variant')
                                            <p class="error">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="">{{__('custom.first_name')}}</label>
                                            <input type="text" name="b_first_name" class="form-control"
                                                   value="{{ old('b_first_name') }}">
                                            @error('b_first_name')
                                            <p class="error">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="form-group col-sm-6">
                                            <label for="">{{__('custom.last_name')}}</label>
                                            <input type="text" name="b_last_name" class="form-control"
                                                   value="{{ old('b_last_name') }}">
                                            @error('b_last_name')
                                            <p class="error">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="form-group col-sm-6">
                                            <label for="">{{__('custom.email')}}</label>
                                            <input type="email" name="b_email" class="form-control"
                                                   value="{{ old('b_email') }}">
                                            @error('b_email')
                                            <p class="error">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="form-group col-sm-6">
                                            <label for="">{{__('custom.phone')}}</label>
                                            <input type="text" name="b_phone" class="form-control phone"
                                                   value="{{ old('b_phone') ?? '+1' }}">
                                            @error('b_phone')
                                            <p class="error">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="">{{__('custom.address_line_1')}}</label>
                                            <input type="text" name="b_address_line_1" class="form-control"
                                                   value="{{ old('b_address_line_1') }}">
                                            @error('b_address_line_1')
                                            <p class="error">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="form-group col-sm-6">
                                            <label for="">{{__('custom.address_line_2')}}</label>
                                            <input type="text" name="b_address_line_2" class="form-control"
                                                   value="{{ old('b_address_line_2') }}">
                                            @error('b_address_line_2')
                                            <p class="error">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group ic-select-gray-bg">
                                                <label for="#">{{ __('custom.country') }}</label>
                                                <select id="country2" name="b_country" class="form-control select2">
                                                    <option value="">{{ __('custom.select') }} {{ __('custom.country') }}
                                                    </option>
                                                    @foreach($countries as $country)
                                                        <option value="{{ $country->id }}">
                                                            {{ $country->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('b_country')
                                                <p class="error">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group ic-select-gray-bg">
                                                <label for="#">{{ __('custom.state') }}</label>
                                                <select id="state2" name="b_state" class="form-control select2">
                                                    <option value="">{{ __('custom.select') }} {{ __('custom.state') }}</option>
                                                </select>

                                                @error('b_state')
                                                <p class="error">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group ic-select-gray-bg">
                                                <label for="#"> {{ __('custom.city') }}</label> <select id="city2" name="b_city"
                                                                                                        class="form-control select2">
                                                    <option value="">{{ __('custom.select') }} {{ __('custom.city') }}</option>
                                                </select>

                                                @error('b_city')
                                                <p class="error">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="">{{__('custom.zipcode')}}</label>
                                            <input type="number" name="b_zipcode" class="form-control"
                                                   maxlength="12" value="{{ old('b_zipcode') }}">
                                            @error('zipcode')
                                            <p class="error">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="form-group col-sm-12">
                                            <label for="b_short_address">{{ __t('short_address') }} <small>({{ __t('short_address_note') }})</small></label>
                                            <textarea name="b_short_address" class="form-control" id="b_short_address"
                                                      placeholder="{{ __t('short_address') }}">{{ old('b_short_address') }}</textarea>
                                            @error('b_short_address')
                                            <p class="error">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group col-md-12 col-lg-12 col-xl-6">
                                    <label for="">{{__('custom.avatar')}}</label>
                                    <small class="font-12">{{ __('custom.image_support_message') }}</small>
                                    <div class="form-group">
                                        <input type="file" id="uploadFile" class="f-input form-control" name="avatar"
                                               value="{{ old('avatar') }}">
                                    </div>
                                    @error('avatar')
                                    <p class="error">{{ $message }}</p>
                                    @enderror
                                </div>


                                <div class="form-group col-sm-6">
                                    <label class="d-block mb-3">{{ __('custom.status') }} <span class="error">*</span></label>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="status_yes" value="{{ \App\Models\Customer::STATUS_ACTIVE }}"
                                               name="status" class="custom-control-input" checked="">
                                        <label class="custom-control-label" for="status_yes">{{__('custom.active')}}</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="status_no" value="{{ \App\Models\Customer::STATUS_INACTIVE }}"
                                               name="status" class="custom-control-input">
                                        <label class="custom-control-label" for="status_no">{{__('custom.inactive')}}</label>
                                    </div>

                                    @error('status')
                                    <p class="error">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <div>
                                    <button class="btn btn-primary waves-effect waves-lightml-2" type="submit">
                                        <i class="fa fa-save"></i> <span>{{ __('custom.submit') }}</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="text-center login-form-footer">© {{ date('Y') }} {{
                            __('custom.all_right_reserved') }} | {{ __('custom.design_and_developed') }} <a class="ic-main-color" href="https://itclanbd.com/"><span class="d-block">ITclan
                                    BD</span></a></div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--================End Login Form Area =================-->

@include('customer.auth.script')
</body>

</html>
