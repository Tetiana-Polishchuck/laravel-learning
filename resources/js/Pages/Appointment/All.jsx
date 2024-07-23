import React, { useState, useEffect, useCallback } from 'react';
import { router, Link } from '@inertiajs/react'

const All = ({ appointments }) => {
    console.log('appointments', appointments);

    const [doctorName, setDoctorName] = useState('');
    const [patientName, setPatientName] = useState('');
    const [dateFilter, setDateFilter] = useState('');
    const [filteredAppointments, setFilteredAppointments] = useState(appointments.data);

    const handlePageChange = (url) => {
        router.get(url, {}, {
            preserveState: true,
            replace: true,
        });
    };

    const debounce = (func, delay) => {
        let timeout;
        return (...args) => {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                func(...args);
            }, delay);
        };
    };

    const handleViewAppointment = (id) => {
        router.get(`/appointments/${id}`);
    };


    const filterAppointments = useCallback(() => {
        const filtered = appointments.data.filter((appointment) => {
            const matchesDoctor = appointment.doctor_name.toLowerCase().includes(doctorName.toLowerCase());
            const matchesPatient = appointment.firstname.toLowerCase().includes(patientName.toLowerCase()) ||
                                   appointment.lastname.toLowerCase().includes(patientName.toLowerCase());
            const matchesDate = dateFilter ? appointment.start_time.includes(dateFilter) : true;

            return matchesDoctor && matchesPatient && matchesDate;
        });
        setFilteredAppointments(filtered);
    }, [appointments.data, doctorName, patientName, dateFilter]);

    const debouncedFilterAppointments = useCallback(debounce(filterAppointments, 500), [filterAppointments]);

    useEffect(() => {
        debouncedFilterAppointments();
    }, [doctorName, patientName, dateFilter, debouncedFilterAppointments]);

    return (
        <div className="container mx-auto p-4">
      <h1 className="text-2xl font-bold mb-4">Appointments</h1>

      {/* Фільтри */}
      <div className="flex gap-4 mb-6">
        <input
          type="text"
          placeholder="Filter by Doctor's Name"
          value={doctorName}
          onChange={(e) => setDoctorName(e.target.value)}
          className="border border-gray-300 p-2 rounded-lg w-1/3"
        />
        <input
          type="text"
          placeholder="Filter by Patient's Name"
          value={patientName}
          onChange={(e) => setPatientName(e.target.value)}
          className="border border-gray-300 p-2 rounded-lg w-1/3"
        />
        <input
          type="date"
          value={dateFilter}
          onChange={(e) => setDateFilter(e.target.value)}
          className="border border-gray-300 p-2 rounded-lg w-1/3"
        />
      </div>

      <table className="min-w-full border-collapse border border-gray-200">
        <thead>
          <tr>
            <th className="border border-gray-200 p-2">ID</th>
            <th className="border border-gray-200 p-2">Doctor Name</th>
            <th className="border border-gray-200 p-2">Specialty</th>
            <th className="border border-gray-200 p-2">Patient Name</th>
            <th className="border border-gray-200 p-2">Phone</th>
            <th className="border border-gray-200 p-2">Email</th>
            <th className="border border-gray-200 p-2">Start Time</th>
            <th className="border border-gray-200 p-2">End Time</th>
          </tr>
        </thead>
        <tbody>
          {filteredAppointments.map((appointment) => (
            <tr key={appointment.appointment_id}>
              <td className="border border-gray-200 p-2">
                <Link href={`/appointments/edit/${appointment.appointment_id}`} className="text-blue-500 underline">
                  {appointment.appointment_id}
                </Link>
              </td>
              <td className="border border-gray-200 p-2">{appointment.doctor_name}</td>
              <td className="border border-gray-200 p-2">{appointment.doctor_specialty}</td>
              <td className="border border-gray-200 p-2">{`${appointment.firstname} ${appointment.lastname}`}</td>
              <td className="border border-gray-200 p-2">{appointment.phonenumber}</td>
              <td className="border border-gray-200 p-2">{appointment.email}</td>
              <td className="border border-gray-200 p-2">{new Date(appointment.start_time).toLocaleString()}</td>
              <td className="border border-gray-200 p-2">{new Date(appointment.end_time).toLocaleString()}</td>
            </tr>
          ))}
        </tbody>
    </table>
    {appointments.last_page > 1 && ( 
        <div className="flex justify-center items-center mt-4 space-x-4">
            <button
                disabled={!appointments.prev_page_url}
                onClick={() => handlePageChange(appointments.prev_page_url)}
                className={`text-white px-4 py-2 rounded-lg ${appointments.prev_page_url ? 'bg-blue-500 hover:bg-blue-600' : 'bg-gray-300 cursor-not-allowed'}`}
            >
                ← Previous
            </button>
            <span className="text-lg">
                Page {appointments.current_page} of {appointments.last_page}
            </span>
            <button
                disabled={!appointments.next_page_url}
                onClick={() => handlePageChange(appointments.next_page_url)}
                className={`text-white px-4 py-2 rounded-lg ${appointments.next_page_url ? 'bg-blue-500 hover:bg-blue-600' : 'bg-gray-300 cursor-not-allowed'}`}
            >
                Next →
            </button>
        </div>
            )}
    </div>
    )
}

export default All;