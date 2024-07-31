import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import NavButton from '@/Components/NavButton';
import useUserRole from './../Pages/UseUserRole';

export default function Dashboard({ auth }) {
    const role = useUserRole(); 

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Dashboard</h2>}
        >
            <Head title="Dashboard" />
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-2">
                        <div className="p-6 text-gray-900 dark:text-gray-100">You're logged in!</div>
                    </div>
                    {(role === 'admin' || role === 'manager') && (
                        <div className="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg inline-flex flex-col mr-2">
                            <NavButton href="/appointments/all" className="w-full sm:w-auto mb-2">Show Appointments</NavButton>
                            <NavButton href="/appointments/create" className="w-full sm:w-auto mb-2">Create New Appointment</NavButton>                    
                            <NavButton href="/patients/all" className="w-full sm:w-auto mb-2">Show Patients</NavButton>
                            <NavButton href="/patients/create" className="w-full sm:w-auto mb-2">Create Patients</NavButton>
                        </div>
                    )}
                    {(role === 'admin') && (
                        <div className="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg inline-flex flex-col">
                            <NavButton href="/doctors/all" className="w-full sm:w-auto mb-2">Show Doctors</NavButton>
                            <NavButton href="/doctor/create" className="w-full sm:w-auto mb-2">Create New Doctor</NavButton>                    
                        </div>
                    )}
                </div>
                
            </div>
        </AuthenticatedLayout>
    );
}
