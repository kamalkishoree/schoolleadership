@extends('admin.layouts.app')

@section('title', 'Create Student')

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Create Student</h2>

        <form action="{{ route('admin.students.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">School</label>
                    <select name="school_id" id="school_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Select School</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
                                {{ $school->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('school_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Class</label>
                    <select name="class_id" id="class_id" required disabled class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Select School first</option>
                    </select>
                    @error('class_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Email or Mobile</label>
                    <input type="text" name="email_or_mobile" value="{{ old('email_or_mobile') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @error('email_or_mobile')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.students.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Create
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('school_id').addEventListener('change', function() {
    const schoolId = this.value;
    const classSelect = document.getElementById('class_id');
    classSelect.innerHTML = '<option value="">Loading...</option>';
    classSelect.disabled = !schoolId;
    
    if (schoolId) {
        classSelect.disabled = true;
        fetch(`/admin/classes/by-school?school_id=${schoolId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    return response.json().then(errorData => {
                        console.error('Error response:', errorData);
                        throw new Error(errorData.message || errorData.error || `HTTP error! status: ${response.status}`);
                    }).catch(() => {
                        return response.text().then(text => {
                            console.error('Error response text:', text);
                            throw new Error(`HTTP error! status: ${response.status}`);
                        });
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Classes data:', data);
                classSelect.innerHTML = '<option value="">Select Class</option>';
                classSelect.disabled = false;
                
                // Handle error response
                if (data.error) {
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'Error: ' + (data.message || data.error);
                    classSelect.appendChild(option);
                    return;
                }
                
                // Handle empty array
                if (!Array.isArray(data) || data.length === 0) {
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'No classes available for this school';
                    classSelect.appendChild(option);
                } else {
                    // Populate classes
                    data.forEach(cls => {
                        const option = document.createElement('option');
                        option.value = cls.id;
                        option.textContent = cls.name;
                        classSelect.appendChild(option);
                    });
                }
            })
            .catch(error => {
                console.error('Error loading classes:', error);
                classSelect.disabled = false;
                classSelect.innerHTML = '<option value="">Error loading classes - Check console</option>';
                alert('Error loading classes. Please check browser console (F12) for details.');
            });
    } else {
        classSelect.innerHTML = '<option value="">Select School first</option>';
    }
});

// Load classes if school is pre-selected (from old input)
@if(old('school_id'))
document.addEventListener('DOMContentLoaded', function() {
    const schoolSelect = document.getElementById('school_id');
    schoolSelect.value = '{{ old('school_id') }}';
    schoolSelect.dispatchEvent(new Event('change'));
    
    // Set old class_id if exists
    @if(old('class_id'))
    setTimeout(() => {
        document.getElementById('class_id').value = '{{ old('class_id') }}';
    }, 500);
    @endif
});
@endif
</script>
@endsection

