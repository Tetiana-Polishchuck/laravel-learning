
import React from 'react';
import { router, Link } from '@inertiajs/react';
import NavButton from '../../Components/NavButton';


const DoctorsList = ({doctors}) => {

    const fetchDoctors = (url) => {
        router.get(url, {}, {
            preserveState: true,
            replace: true,
        });
    };

    
    return (
        <div className="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
            <NavButton href="/dashboard" className="w-full sm:w-auto mb-2">Dashboard</NavButton>
            <NavButton href="/doctors/create" className="w-full sm:w-auto mb-2 ml-2">Create Doctor</NavButton>

            <h2 className="text-lg font-semibold text-gray-800 dark:text-white mb-4">Doctors List</h2>
                <table className="min-w-full bg-white dark:bg-gray-900">
                    <thead>
                        <tr>
                            <th className="py-2 px-4 border-b">ID</th>
                            <th className="py-2 px-4 border-b">Name</th>
                            <th className="py-2 px-4 border-b">Specialty</th>
                            <th className="py-2 px-4 border-b">Done Appointment</th>
                            <th className="py-2 px-4 border-b">Planned Appointment</th>
                            <th className="py-2 px-4 border-b">Active</th>
                            <th className="py-2 px-4 border-b">Vacation</th>
                            <th className="py-2 px-4 border-b">Sick</th>
                        </tr>
                    </thead>
                    <tbody>
                        {doctors.data.map((doctor) => (
                            <tr key={doctor.id}>
                                <td className="py-2 px-4 border-b">{doctor.id}</td>
                                <td className="py-2 px-4 border-b"><Link href={`doctors/${doctor.id}`} className="text-blue-500 underline">{doctor.name}</Link></td>
                                <td className="py-2 px-4 border-b">{doctor.specialty}</td>
                                <td className="py-2 px-4 border-b">{doctor.done_visits}</td>
                                <td className="py-2 px-4 border-b">{doctor.planed_visits}</td>
                                <td className="py-2 px-4 border-b">{doctor.is_active ? 'Yes' : 'No'}</td>
                                <td className="py-2 px-4 border-b">{doctor.is_on_vacation ? 'Yes' : 'No'}</td>
                                <td className="py-2 px-4 border-b">{doctor.is_on_sick_leave ? 'Yes' : 'No'}</td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            

            {/* Пагінація */}
            {doctors.last_page > 1 && ( 
                <div className="flex justify-center items-center mt-4 space-x-4">
                    <button
                        disabled={!doctors.prev_page_url}
                        onClick={() => fetchDoctors(doctors.prev_page_url)}
                        className={`text-white px-4 py-2 rounded-lg ${doctors.prev_page_url ? 'bg-blue-500 hover:bg-blue-600' : 'bg-gray-300 cursor-not-allowed'}`}
                    >
                        ← Previous
                    </button>
                    <span className="text-lg">
                        Page {doctors.current_page} of {doctors.last_page}
                    </span>
                    <button
                        disabled={!doctors.next_page_url}
                        onClick={() => fetchDoctors(doctors.next_page_url)}
                        className={`text-white px-4 py-2 rounded-lg ${doctors.next_page_url ? 'bg-blue-500 hover:bg-blue-600' : 'bg-gray-300 cursor-not-allowed'}`}
                    >
                        Next →
                    </button>
                </div>
                )}
        </div>
    );
};

export default DoctorsList;
