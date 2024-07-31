import React, { useState, useEffect, useCallback } from 'react';
import { router } from '@inertiajs/react'
import NavButton from '../../Components/NavButton';

const Create = ({ doctors, appointment }) => {
    console.log('appointment', appointment ? appointment : 'no appointment');
    const formatDateTime = (dateTime) => {
        const date = new Date(dateTime);
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
    
        return `${year}-${month}-${day}T${hours}:${minutes}`;
    };

    const [selectedDoctor, setSelectedDoctor] = useState(appointment ? appointment.doctor_id : '');
    const [selectedSpecialty, setSelectedSpecialty] = useState(appointment ? appointment.doctor.specialty : '');
    const [specialties, setSpecialties] = useState([]);
    const [filteredDoctors, setFilteredDoctors] = useState(doctors);
    const [patientSearch, setPatientSearch] = useState('');
    const [patients, setPatients] = useState([]);
    const [selectedPatientId, setSelectedPatientId] = useState(appointment ? appointment.patient_id : '');
    const [selectedPatientName, setSelectedPatientName] = useState(appointment ? appointment.patient.firstname +  appointment.patient.lastname : '');
    const [appointmentStart, setAppointmentStart] = useState(appointment ? formatDateTime(appointment.start_time) : '');
    const [appointmentEnd, setAppointmentEnd] = useState(appointment ? formatDateTime(appointment.end_time) : '');
    const [formErrors, setFormErrors] = useState(true);
    const [isLoading, setIsLoading] = useState(false);
    const [noResults, setNoResults] = useState(false);
    
    const location = window.location;

    useEffect(() => {
        const uniqueSpecialties = [...new Set(doctors.map(doctor => doctor.specialty))];
        setSpecialties(uniqueSpecialties);

        const queryParams = new URLSearchParams(location.search);
        const patientId = queryParams.get('patient');
        if (patientId) {
            const fetchPatient = async () => {
                const response = await fetch(`/patients/patient/${patientId}`);
                const data = await response.json();

                if (data) {
                    setSelectedPatientId(data.id);
                    setSelectedPatientName(`${data.firstname} ${data.lastname}`);
                }
            };

            fetchPatient();
        }

    }, [doctors, location.search]);

    const handleSpecialtyChange = (e) => {
        const specialty = e.target.value;
        setSelectedSpecialty(specialty);
        setFilteredDoctors(doctors.filter(doctor => doctor.specialty === specialty));
    };

    const handleDoctorChange = (e) => {
        setSelectedDoctor(e.target.value);
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

    const searchPatients = async (searchTerm) => {
        if (searchTerm.length >= 3) {
            setIsLoading(true);
            const response = await fetch(`/patients/search?query=${searchTerm}`);
            const data = await response.json();
            setPatients(data);
            setIsLoading(false);
            setNoResults(data.length === 0);
        } else {
            setPatients([]);
            setIsLoading(false);
        }
    };

    const debouncedSearchPatients = useCallback(debounce(searchPatients, 500), []);


    const handlePatientSearch = async (e) => {
        const searchTerm = e.target.value;
        setPatientSearch(searchTerm);
        setNoResults(false);       
        debouncedSearchPatients(searchTerm);
    };

    const handlePatientSelect = (patient) => {
        setSelectedPatientId(patient.id);
        setSelectedPatientName(`${patient.firstname} ${patient.lastname}`);
        setPatientSearch('');
        setPatients([]);
    };

    const handleStartChange = (e) => {
        const startTime = e.target.value;
        const startDateTime = new Date(startTime);
        const currentDateTime = new Date();

        if (startDateTime < currentDateTime) {
            setFormErrors('Start time cannot be earlier than the current time.');
            return;
        }

        setAppointmentStart(startTime);

        if (!appointmentEnd) {
            const endDateTime = new Date(startDateTime.getTime() + 30 * 60000);
            const offset = endDateTime.getTimezoneOffset();
            const localEndDateTime = new Date(endDateTime.getTime() - (offset * 60000));
            setAppointmentEnd(localEndDateTime.toISOString().slice(0, 16));
        } else {
            const endDateTime = new Date(appointmentEnd);            
            if (endDateTime <= startDateTime) {
                const offset = endDateTime.getTimezoneOffset();
                const newEndDateTime = new Date(startDateTime.getTime() - (offset * 60000) + 30 * 60000);
                setAppointmentEnd(newEndDateTime.toISOString().slice(0, 16));
            }
        }
    };

    const handleEndChange = (e) => {
        const endTime = e.target.value;
        const startDateTime = new Date(appointmentStart);
        const endDateTime = new Date(endTime);        

        if (endDateTime <= startDateTime) {
            setFormErrors('End time cannot be earlier than start time.');
            return;
        }

        setAppointmentEnd(endTime);
    };


    const handleSubmit = (e) => {
        e.preventDefault();
        if (!selectedDoctor || !selectedPatientId || !appointmentStart || !appointmentEnd) {
            setFormErrors('Please fill out all fields.');
            return;
        }
        setFormErrors('');
        if(!appointment){
            router.post('/appointments/new', {
                doctor_id: +selectedDoctor,
                patient_id: selectedPatientId,
                start_time: appointmentStart,
                end_time: appointmentEnd,
            }, {            
                onError: (errors) => {
                    if(typeof(errors) == 'object'){
                        alert(Object.values(errors));
                    }else{
                        alert(errors);
                    }
                }
            });
        } else{
            router.patch('/appointments/'+appointment.id, {
                doctor_id: +selectedDoctor,
                patient_id: selectedPatientId,
                start_time: appointmentStart,
                end_time: appointmentEnd,
            }, {            
                onError: (errors) => {
                    console.log('errors', errors);
                    if(typeof(errors) == 'object'){
                        alert(Object.values(errors));
                    }else{
                        alert(errors);
                    }
                }
            });
        }
        
    };

    const isFormValid = selectedDoctor && selectedPatientId && appointmentStart && appointmentEnd;


    return (
        <div className="appointment-form-container">
            <NavButton href="/dashboard" className="w-full sm:w-auto mb-2">Dashboard</NavButton>
            <h1 id="appointment-form-container-title">{appointment ? 'Update Appointment' : 'Create Appointment'}</h1>
            <form onSubmit={handleSubmit} className="appointment-form">
                <div className="form-group">
                <label htmlFor="specialtySelect">Specialty</label>
                    <select id="specialtySelect" value={selectedSpecialty} onChange={handleSpecialtyChange} className="form-control">
                        <option value="">Select Specialty</option>
                        {specialties.map(specialty => (
                            <option key={specialty} value={specialty}>{specialty}</option>
                        ))}
                    </select>
                </div>
                <div className="form-group">
                    <label htmlFor="doctorSelect">Doctor:</label>
                    <select id="doctorSelect" value={selectedDoctor} onChange={handleDoctorChange} className="form-control">
                        <option value="">Select Doctor</option>
                        {filteredDoctors.map(doctor => (
                            <option key={doctor.id} value={doctor.id}>{doctor.name}</option>
                        ))}
                    </select>
                </div>
                <div className="form-group" id="patient-form-group">
                    <label htmlFor="patientInput">Patient:</label>
                    <input
                        id="patientInput"
                        type="text"
                        value={patientSearch || selectedPatientName} 
                        onChange={handlePatientSearch}
                        className="form-control"
                        placeholder="Search patient by name or phone"
                    />
                    {isLoading && <div className="container-loader"><div className="loader"></div></div>}
                    {noResults && <div className="no-results">No patients found</div>}
                    {patients.length > 0 && (
                        <ul className="patient-list">
                            {patients.map(patient => (
                                <li key={patient.id} onClick={() => handlePatientSelect(patient)}>
                                    {patient.firstname} {patient.lastname} - {patient.phonenumber}
                                </li>
                            ))}
                        </ul>
                    )}
                    {selectedPatientName && (
                        <div className="selected-patient">
                            Selected Patient: {selectedPatientName}
                        </div>
                    )}
                </div>                
                 <div className="form-group">
                    <label htmlFor="startTimeInput">Start Appointment Time:</label>
                    <input
                        id="startTimeInput"
                        type="datetime-local"
                        name="appointmentStart"
                        value={appointmentStart}
                        onChange={handleStartChange}
                    />
                </div>
                <div className="form-group">
                    <label htmlFor="endTimeInput">End Appointment Time:</label>
                    <input
                        id="endTimeInput"
                        type="datetime-local"
                        name="appointmentEnd"
                        value={appointmentEnd}
                        onChange={handleEndChange}
                    />
                </div>
                {formErrors && <p className="form-errors">{formErrors}</p>}
                <button type="submit" className="submit-button" disabled={!isFormValid}>{appointment ? 'Update Appointment' : 'Create Appointment'}</button>
            </form>
        </div>
    );
};

export default Create;
