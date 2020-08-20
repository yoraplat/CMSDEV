import { default as React, useContext, useState, createContext } from 'react';

import { apiConfig } from '../config';

import { useHistory, useParams } from 'react-router-dom';
import * as Routes from '../routes';

const AuthContext = createContext();
const useAuth = () => useContext(AuthContext);


const AuthProvider = ({children}) => {
  
    let history = useHistory();
    
    const checkIfLoggedIn = () => {
      if (localStorage.getItem('csight:user')) {
        return true;
      } else {
        return false;
      }
    };

    const currentUserId = () => {
      return JSON.parse(localStorage.getItem('csight:user')).id;
    }

    const getUser = () => {
      return JSON.parse(localStorage.getItem('csight:user'));
    }

    const [currentUser, setCurrentUser] = useState(getUser());

  return (
    <AuthContext.Provider value={{ checkIfLoggedIn , currentUser, setCurrentUser, getUser, currentUserId }}>
      {children}
    </AuthContext.Provider>
  );
};

export {
  AuthContext,
  AuthProvider,
  useAuth,
}