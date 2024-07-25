
import React, { useState, useEffect } from 'react';
import { router, Link } from '@inertiajs/react';
import NavButton from '../../Components/NavButton';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';

import { faCalendarPlus } from '@fortawesome/free-solid-svg-icons';

const PatientsList = ({patients}) => {

    const fetchPatients = (url) => {
        router.get(url, {}, {
            preserveState: true,
            replace: true,
        });
    };

    
    return (
        <div className="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
            <NavButton href="/dashboard" className="w-full sm:w-auto mb-2">Dashboard</NavButton>
            <h2 className="text-lg font-semibold text-gray-800 dark:text-white mb-4">Patients List</h2>
                <table className="min-w-full bg-white dark:bg-gray-900">
                    <thead>
                        <tr>
                            <th className="py-2 px-4 border-b">First Name</th>
                            <th className="py-2 px-4 border-b">Last Name</th>
                            <th className="py-2 px-4 border-b">Phone Number</th>
                            <th className="py-2 px-4 border-b">Email</th>
                            <th className="py-2 px-4 border-b">Last Visit</th>
                            <th className="py-2 px-4 border-b">Number of Visits</th>
                            <th className="py-2 px-4 border-b">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {patients.data.map((patient) => (
                            <tr key={patient.id}>
                                <td className="py-2 px-4 border-b">{patient.firstname}</td>
                                <td className="py-2 px-4 border-b">{patient.lastname}</td>
                                <td className="py-2 px-4 border-b">{patient.phonenumber}</td>
                                <td className="py-2 px-4 border-b">{patient.email}</td>
                                <td className="py-2 px-4 border-b">
                                    {patient.last_visit}
                                </td>
                                <td className="py-2 px-4 border-b">{patient.number_visits}</td>
                                <td className="py-2 px-4 border-b" title="Create Appointment">
                                    <Link href={`/appointments/create?patient=${patient.id}`}>
                                    <FontAwesomeIcon
                                        icon={faCalendarPlus}
                                        className="cursor-pointer text-blue-500 hover:text-blue-700"
                                    />
                                    </Link>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            

            {/* Пагінація */}
            {patients.last_page > 1 && ( 
                <div className="flex justify-center items-center mt-4 space-x-4">
                    <button
                        disabled={!patients.prev_page_url}
                        onClick={() => fetchPatients(patients.prev_page_url)}
                        className={`text-white px-4 py-2 rounded-lg ${patients.prev_page_url ? 'bg-blue-500 hover:bg-blue-600' : 'bg-gray-300 cursor-not-allowed'}`}
                    >
                        ← Previous
                    </button>
                    <span className="text-lg">
                        Page {patients.current_page} of {patients.last_page}
                    </span>
                    <button
                        disabled={!patients.next_page_url}
                        onClick={() => fetchPatients(patients.next_page_url)}
                        className={`text-white px-4 py-2 rounded-lg ${patients.next_page_url ? 'bg-blue-500 hover:bg-blue-600' : 'bg-gray-300 cursor-not-allowed'}`}
                    >
                        Next →
                    </button>
                </div>
                )}
        </div>
    );
};

export default PatientsList;
