@extends(\Flamarkt\Core\Notification\MailConfig::$templateView)

@section('content')
    <p>{!! \Flamarkt\NativeEmailOverrides\MailHelper::trans($translator, 'flamarkt-native-email-overrides.email.resetPassword.body', $originalTranslatorData) !!}</p>

    <div class="ButtonBlock">
        <a href="{{ $originalTranslatorData['url'] }}"
           class="Button">{{ $translator->trans('flamarkt-native-email-overrides.email.resetPassword.action') }}</a>
    </div>
@endsection
