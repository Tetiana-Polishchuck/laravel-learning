import React, { useState } from 'react';
import { useForm } from '@inertiajs/react';

const CreatePatient = () => {
    const { data, setData, post, processing, errors, reset } = useForm({
        firstname: '',
        lastname: '',
        email: '',
        phonenumber: ''
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        if (!data.firstname || !data.lastname || !data.email || !data.phonenumber) {
            alert('Будь ласка, заповніть всі поля.');
            return;
        }

        if (!/\S+@\S+\.\S+/.test(data.email)) {
            alert('Будь ласка, введіть дійсну електронну адресу.');
            return;
        }

        post('/patients/new', {
            onSuccess: () => reset(),
        });
    };

    return (
        <div className="max-w-md mx-auto p-4">
            <h1 className="text-2xl font-bold mb-4">Create Patient</h1>
            <form onSubmit={handleSubmit} className="space-y-4">
                <div>
                    <label htmlFor="firstname" className="block text-sm font-medium text-gray-700">First Name</label>
                    <input
                        type="text"
                        id="firstname"
                        name="firstname"
                        value={data.firstname}
                        onChange={(e) => setData('firstname', e.target.value)}
                        className="mt-1 p-2 block w-full border border-gray-300 rounded-md"
                    />
                    {errors.firstname && <div className="text-red-600 text-sm">{errors.firstname}</div>}
                </div>
                <div>
                    <label htmlFor="lastname" className="block text-sm font-medium text-gray-700">Last Name</label>
                    <input
                        type="text"
                        id="lastname"
                        name="lastname"
                        value={data.lastname}
                        onChange={(e) => setData('lastname', e.target.value)}
                        className="mt-1 p-2 block w-full border border-gray-300 rounded-md"
                    />
                    {errors.lastname && <div className="text-red-600 text-sm">{errors.lastname}</div>}
                </div>
                <div>
                    <label htmlFor="email" className="block text-sm font-medium text-gray-700">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value={data.email}
                        onChange={(e) => setData('email', e.target.value)}
                        className="mt-1 p-2 block w-full border border-gray-300 rounded-md"
                    />
                    {errors.email && <div className="text-red-600 text-sm">{errors.email}</div>}
                </div>
                <div>
                    <label htmlFor="phonenumber" className="block text-sm font-medium text-gray-700">Phone Number</label>
                    <input
                        type="tel"
                        id="phonenumber"
                        name="phonenumber"
                        value={data.phonenumber}
                        onChange={(e) => setData('phonenumber', e.target.value)}
                        className="mt-1 p-2 block w-full border border-gray-300 rounded-md"
                    />
                    {errors.phonenumber && <div className="text-red-600 text-sm">{errors.phonenumber}</div>}
                </div>
                <button
                    type="submit"
                    disabled={processing}
                    className="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:bg-blue-700"
                >
                    {processing ? 'Saving...' : 'Create Patient'}
                </button>
            </form>
        </div>
    );
};

export default CreatePatient;
