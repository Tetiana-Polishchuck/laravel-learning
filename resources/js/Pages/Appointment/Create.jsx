import React, { useState, useEffect } from 'react';
import { Inertia } from '@inertiajs/inertia';

const Create = ({ doctors }) => {

    const [selectedDoctor, setSelectedDoctor] = useState('');
    const [selectedSpecialty, setSelectedSpecialty] = useState('');
    const [specialties, setSpecialties] = useState([]);
    const [filteredDoctors, setFilteredDoctors] = useState(doctors);
    const [patientSearch, setPatientSearch] = useState('');
    const [patients, setPatients] = useState([]);
    const [selectedPatientId, setSelectedPatientId] = useState('');
    const [selectedPatientName, setSelectedPatientName] = useState('');
    const [appointmentDate, setAppointmentDate] = useState('');
    const [appointmentTime, setAppointmentTime] = useState('');
    const [formErrors, setFormErrors] = useState(true);
    const [isLoading, setIsLoading] = useState(false);


    useEffect(() => {
        const uniqueSpecialties = [...new Set(doctors.map(doctor => doctor.specialty))];
        setSpecialties(uniqueSpecialties);
    }, [doctors]);

    const handleSpecialtyChange = (e) => {
        const specialty = e.target.value;
        setSelectedSpecialty(specialty);
        setFilteredDoctors(doctors.filter(doctor => doctor.specialty === specialty));
    };

    const handleDoctorChange = (e) => {
        setSelectedDoctor(e.target.value);
    };

    const handlePatientSearch = async (e) => {
        const searchTerm = e.target.value;
        setPatientSearch(searchTerm);

        if (searchTerm.length >= 3) {
            setIsLoading(true);
            const response = await fetch(`/patients/search?query=${searchTerm}`);
            const data = await response.json();
            setPatients(data);
            setIsLoading(false);
        } else {
            setPatients([]);
        }
    };

    const handlePatientSelect = (patient) => {
        setSelectedPatientId(patient.id);
        setSelectedPatientName(`${patient.firstname} ${patient.lastname}`);
        setPatientSearch('');
        setPatients([]);
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        if (!selectedDoctor || !selectedPatientId || !appointmentDate || !appointmentTime) {
            setFormErrors('Please fill out all fields.');
            return;
        }
        setFormErrors('');
        Inertia.post('/appointments/new', {
            doctor_id: selectedDoctor,
            patient_id: selectedPatientId,
            date: appointmentDate,
            time: appointmentTime
        });
    };

    const isFormValid = selectedDoctor && selectedPatientId && appointmentDate && appointmentTime;


    return (
        <div className="appointment-form-container">
            <h1 id="appointment-form-container-title">Create Appointment</h1>
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
                    {patients.length > 0 && (
                        <ul className="patient-list">
                            {patients.map(patient => (
                                <li key={patient.id} onClick={() => handlePatientSelect(patient)}>
                                    {patient.firstname} {patient.lastname} - {patient.phonenumber}
                                </li>
                            ))}
                        </ul>
                    )}
                </div>
                <div className="form-group">
                    <label htmlFor="dateInput">Appointment Date:</label>
                    <input
                        id="dateInput"
                        type="date"
                        name="appointmentDate"
                        value={appointmentDate}
                        onChange={(e) => setAppointmentDate(e.target.value)}
                    />
                </div>
                <div className="form-group">
                    <label htmlFor="timeInput">Appointment Time:</label>
                    <input
                        id="timeInput"
                        type="time"
                        name="appointmentTime"
                        value={appointmentTime}
                        onChange={(e) => setAppointmentTime(e.target.value)}
                    />
                </div>
                {formErrors && <p className="form-errors">{formErrors}</p>}
                <button type="submit" className="submit-button" disabled={!isFormValid}>Create Appointment</button>
            </form>
        </div>
    );
};

export default Create;
