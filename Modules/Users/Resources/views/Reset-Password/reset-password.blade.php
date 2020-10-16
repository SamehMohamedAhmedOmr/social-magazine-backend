@extends('notifications::Emails.layout')

@section('email-content')

    <div>
        <p>لم تم ارسال هذا البريد الالكتروني بناء على طلب من حسابك</p>
        <p>الرقم التأكيدي هو <strong>{{ $render_data['token'] }}</strong>  </p>
        <p>اذا لم تقم بطلب اعادة تعيين كلمة مرورك ، لا يجب ان تتخذ اي اجراء آخر</p>
    </div>

@endsection
