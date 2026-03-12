@extends('layouts.admin')

@section('content')
    <div class="mb-5">
        <h2 class="fw-bold">General Settings</h2>
        <p class="text-muted">Global platform configuration and API management.</p>
    </div>

    <div class="row">
        <div class="col-lg-8">
            @if (session('success'))
                <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h5 class="fw-bold mb-4">AI Configuration (Groq)</h5>
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Groq API Key</label>
                        <div class="input-group">
                            <input type="password" name="groq_api_key" id="groq_api_key" class="form-control"
                                value="{{ $settings['groq_api_key'] }}">
                            <button class="btn btn-outline-secondary" type="button" id="toggle_key">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <small class="text-muted">Currently using: {{ substr($settings['groq_api_key'], 0, 7) }}...</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Groq Model Name</label>
                        @php
                            $commonModels = [
                                'llama-3.1-8b-instant' => 'Llama 3.1 8B (Fast)',
                                'llama-3.3-70b-versatile' => 'Llama 3.3 70B (Smart)',
                                'llama-3.1-70b-versatile' => 'Llama 3.1 70B',
                                'mixtral-8x7b-32768' => 'Mixtral 8x7B',
                                'gemma2-9b-it' => 'Gemma 2 9B',
                            ];
                            $isCustom = !array_key_exists($settings['groq_model'], $commonModels);
                        @endphp
                        <select id="model_selector" class="form-select mb-2">
                            @foreach ($commonModels as $value => $label)
                                <option value="{{ $value }}"
                                    {{ $settings['groq_model'] == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                            <option value="custom" {{ $isCustom ? 'selected' : '' }}>Other (Custom...)</option>
                        </select>

                        <input type="text" name="groq_model" id="custom_model_input"
                            class="form-control {{ $isCustom ? '' : 'd-none' }}" placeholder="Enter custom model name"
                            value="{{ $settings['groq_model'] }}">
                        <small class="text-muted">Choose a recommended model or enter a custom one.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Default Prompt Template</label>
                        <textarea name="default_prompt" class="form-control" rows="5">{{ $settings['default_prompt'] }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Config</button>
                </form>
            </div>
            {{-- 
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h5 class="fw-bold mb-4">Platform Maintenance</h5>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-switch" type="checkbox" id="maintenance">
                    <label class="form-check-label" for="maintenance">Enable Maintenance Mode</label>
                </div>
                <button class="btn btn-outline-danger">Purge Expired AI Jobs</button>
            </div> --}}
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('model_selector').addEventListener('change', function() {
            const customInput = document.getElementById('custom_model_input');
            if (this.value === 'custom') {
                customInput.classList.remove('d-none');
                customInput.value = '';
                customInput.focus();
            } else {
                customInput.classList.add('d-none');
                customInput.value = this.value;
            }
        });

        document.getElementById('toggle_key').addEventListener('click', function() {
            const keyInput = document.getElementById('groq_api_key');
            const icon = this.querySelector('i');
            if (keyInput.type === 'password') {
                keyInput.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                keyInput.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        });
    </script>
@endpush
