@extends(\Flamarkt\Core\Notification\MailConfig::$templateView)

@section('content')
    <p>{!! \Flamarkt\NativeEmailOverrides\MailHelper::trans($translator, 'flamarkt-native-email-overrides.email.confirmEmail.body', $originalTranslatorData) !!}</p>

    <div class="ButtonBlock">
        <a href="{{ $originalTranslatorData['url'] }}"
           class="Button">{{ $translator->trans('flamarkt-native-email-overrides.email.confirmEmail.action') }}</a>
    </div>
@endsection
