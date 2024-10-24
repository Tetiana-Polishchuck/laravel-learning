import React, {useState} from "react";
import { router } from "@inertiajs/react";
import Authenticated from '@/Layouts/AuthenticatedLayout';


const CreateDoctor = ({ doctor, auth }) => {

    const [name, setName] = useState(doctor ? doctor.name : '');
    const [specialty, setSpecialty] = useState(doctor ? doctor.specialty : '');
    const [phone, setPhone] = useState(doctor ? doctor.phone : '');
    const [email, setEmail] = useState(doctor ? doctor.email : '');
    const [isActive, setIsActive] = useState(doctor ? doctor.is_active : true);
    const [isOnVacation, setIsOnVacation] = useState(doctor ? doctor.is_on_vacation : false);
    const [isSickLeave, setIsSickLeave] = useState(doctor ? doctor.is_on_sick_leave : false);

    const handleActiveChange = (e) => {
        setIsActive(e.target.checked);
        if (!e.target.checked) {
            setIsOnVacation(false);
            setIsSickLeave(false);
        }
    };

    const handleVacationChange = (e) => {
        if (!isActive) {
            alert("Vacation can only be set if Active is checked.");
            return;
        }
        if (e.target.checked && isSickLeave) {
            alert("Cannot set both Vacation and Sick leave at the same time.");
            return;
        }
        setIsOnVacation(e.target.checked);
    };

    const handleSickLeaveChange = (e) => {
        if (!isActive) {
            alert("Sick leave can only be set if Active is checked.");
            return;
        }
        if (e.target.checked && isOnVacation) {
            alert("Cannot set both Vacation and Sick leave at the same time.");
            return;
        }
        setIsSickLeave(e.target.checked);
    };

    const handleCreate = () => {
        if (name.trim() === '' || specialty.trim() === '') {
          alert('Please fill in the name and the spacialty');
          return;
        }

        if (phone.length < 9 || phone.length > 30) {
            alert('Phone number must be between 9 and 30 characters.');
            return;
        }

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            alert('Please enter a valid email address.');
            return;
        }
        if (email.length > 254) {
            alert('Email address must be no more than 254 characters.');
            return;
        }

        const requestData = {
            name: name,
            specialty: specialty,
            phone:phone,
            email:email,
            is_active: isActive,
            is_on_vacation: isOnVacation,
            is_on_sick_leave:isSickLeave
        };

        if(!doctor){
            router.post('/doctors/store', requestData, {            
                onError: (errors) => {
                    if(typeof(errors) == 'object'){
                        alert(Object.values(errors));
                    }else{
                        alert(errors);
                    }
                }
            });
        } else{
            router.patch('/doctors/'+doctor.id, requestData, {            
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

      return (
        <Authenticated
            user={auth.user}
        >
            <div className="doctor-container">
                <div className="title">Create Doctor</div>
                <form>
                    <div>
                        <label className="name-label" htmlFor="name">Name</label>
                        <input
                            id="name"
                            type="text"
                            value={name}
                            onChange={(e) => setName(e.target.value)}
                        />
                    </div>
                    <div>
                        <label className="specialty-label" htmlFor="specialty">Specialty</label>
                        <input
                            id="specialty"
                            className="custom-checkbox"
                            type="text"
                            value={specialty}
                            onChange={(e) => setSpecialty(e.target.value)}
                        />
                    </div>
                    <div>
                        <label className="phone-label" htmlFor="phone">Phone number</label>
                        <input
                            id="phone"
                            className="custom-checkbox"
                            type="text"
                            value={phone}
                            onChange={(e) => setPhone(e.target.value)}
                        />
                    </div>
                    <div>
                        <label className="email-label" htmlFor="email">Email</label>
                        <input
                            id="email"
                            className="custom-checkbox"
                            type="text"
                            value={email}
                            onChange={(e) => setEmail(e.target.value)}
                        />
                    </div>
                    <div className="active-container">                    
                        <input
                        id="active-input"
                        type="checkbox"
                        className="custom-checkbox"
                        checked={isActive}
                        onChange={handleActiveChange}
                        />
                        <label htmlFor="active-input">Active</label>
                    </div>
                    <div className="vacation-container">
                        <input
                        id="vacation-input"
                        className="custom-checkbox"
                        type="checkbox"
                        checked={isOnVacation}
                        onChange={handleVacationChange}
                        />
                        <label htmlFor="vacation-input">Vacation</label>
                    </div>
                    <div className="sick-container">                    
                        <input
                        id="sick-input"
                        type="checkbox"
                        checked={isSickLeave}
                        onChange={handleSickLeaveChange}
                        />                        
                        <label htmlFor="sick-input">Sick</label>
                    </div>
                    <button
                    className="mt-2"
                    id="submit-button"
                    type="button"
                    onClick={handleCreate}
                    >
                    {doctor ? 'Update' : 'Create'}
                    </button>
            </form>
            </div>
        </Authenticated>    
      );
}

export default CreateDoctor;
