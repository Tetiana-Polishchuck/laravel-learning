
import { useState, useEffect } from 'react';
import axios from 'axios';

const useUserRole = () => {
    const [role, setRole] = useState(null);

    useEffect(() => {
        axios.get('/user/role').then(response => {
            setRole(response.data.role);
        }).catch(error => {
            console.error('Failed to fetch user role:', error);
        });
    }, []);

    return role;
};

export default useUserRole;
