import React, { useState, useEffect, useCallback } from 'react';
import { router, Link } from '@inertiajs/react';
import Authenticated from '@/Layouts/AuthenticatedLayout';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faTrash } from "@fortawesome/free-solid-svg-icons";


const All = ({ appointments, auth }) => {

    const [doctorName, setDoctorName] = useState('');
    const [patientName, setPatientName] = useState('');
    const [dateFilter, setDateFilter] = useState('');
    const [filteredAppointments, setFilteredAppointments] = useState(appointments.data);
    const token = document.querySelector("[name='_token']").value;


    const handlePageChange = (url) => {
        router.get(url, {}, {
            preserveState: true,
            replace: true,
        });
    };

    const handleDelete = (id) =>{
      if (confirm('Are you sure you want to delete this appointment?')) {
        fetch('/appointments/' + id, {
          method: 'DELETE',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token 
        },
        })
        .then((res) => res.json())
        .then((data) => {
          alert(data.message);
          fetchAppointments();
        })
        .catch(err => alert(err));   
      }
    }

    const fetchAppointments = async () => {
      try {
        const response = await fetch(`/appointments/index/${appointments.current_page}`);
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        const data = await response.json();
        const newAppointments = data.data;
        if (newAppointments.length === 0 && appointments.current_page > 1) {
          const prevPageResponse = await fetch(`/appointments/index/${appointments.current_page - 1}`);
          const prevPageData = await prevPageResponse.json();
          setFilteredAppointments(prevPageData.data);
        } else {
          setFilteredAppointments(newAppointments.data);
        }
      } catch (error) {
        console.error('Fetch error:', error);
      }
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

    /*const handleViewAppointment = (id) => {
        router.get(`/appointments/${id}`);
    };*/


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
      <Authenticated
            user={auth.user}
      >
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
                <th className="border border-gray-200 p-2">Action</th>
              </tr>
            </thead>
            <tbody>
              {filteredAppointments.map((appointment) => (
                <tr key={appointment.appointment_id}>
                  <td className="border border-gray-200 p-2">
                    <Link href={`/appointments/${appointment.appointment_id}`} className="text-blue-500 underline">
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
                  <td className="border border-gray-200 p-2">
                    <FontAwesomeIcon onClick={() => handleDelete(appointment.appointment_id)} title='Delete Appointment' className='trash-icon' icon={faTrash} />
                  </td>
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
      </Authenticated>  
    )
}

export default All;