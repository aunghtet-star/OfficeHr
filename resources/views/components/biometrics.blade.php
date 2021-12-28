@foreach ($biometrics as $biometric)
<a href="#" class="btn bio-register-btn">
    <i class="fas fa-fingerprint finger-color"></i>
    <p class="mb-0 mt-1">finger 1</p>
    <i class="fas fa-trash-alt bio-delete-btn" data-id="{{$biometric->id}}" ></i>
</a>
@endforeach