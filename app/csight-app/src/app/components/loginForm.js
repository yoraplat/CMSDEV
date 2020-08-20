import { default as React, useCallback, useEffect, useState } from 'react';
import { useHistory, useParams } from 'react-router-dom';
import * as Routes from '../routes';


import bcrypt from 'react-native-bcrypt';
import { useApi } from '../services';
import { useAuth } from '../services';
import { apiConfig } from '../config';

const BASE_URL = `${apiConfig.baseURL}`;
const LoginForm = () => {

    const { findUsers } = useApi();
    const { checkIfLoggedIn } = useAuth();
    const [ users, setUsers ] = useState();

    let history = useHistory();

    const [loginForm, setLoginForm] = useState({
        txtEmail: '',
        txtPassword: ''
      });

    const initFetch = useCallback(
        () => {
          const fetchUsers = async () => {
            const data = await findUsers({
            });
            setUsers(data['hydra:member']);
          }
          fetchUsers();
        },
        [findUsers],
      )

      useEffect(() => {
        initFetch();
        return () => {
          // no cleanup
        }
      }, [initFetch]);

    const handleSubmit = async (ev) => {
        ev.preventDefault();
        if (checkIfLoggedIn() === false) {

          const post = {
              email: loginForm.txtEmail,
              password: loginForm.txtPassword
            };
          
          let userFound = users.find(users => users.email === post.email);
  
          let hash = userFound.password.replace('$2y$', '$2a$');
  
          if (bcrypt.compareSync(post.password, hash) === true) {
            
            // Set logged in user
            localStorage.setItem('csight:user', JSON.stringify(userFound));
  
            // redirect to dashboard
            history.push(Routes.HOME);
          };
        }
    }

    const handleInputChange = (ev) => {
        setLoginForm({
          ...loginForm,
          [ev.target.name]: ev.target.value
        });
      }

    const logout = (ev) => {
      ev.preventDefault()
      localStorage.removeItem('csight:user');
      history.go();
    }
    
    
    return (
        <div className="login-form">
            {/* { users && users.map((user, index ) => (
                <p>{user.email}</p>
                
            ))} */}
            {
              checkIfLoggedIn() ? 
              <div>
                <h3>Je bent al ingelogd</h3>
                <a href="#" onClick={logout}>Uitloggen</a>
              </div>
              :  
            <form onSubmit={(ev) => handleSubmit(ev)}>
                <div className="login-row">
                    <label for="email">Email:</label>
                    <input name="email" placeholder="Email" id="txtEmail" name="txtEmail" onChange={handleInputChange} />
                </div>
                <div className="login-row">
                    <label for="password">Password:</label>
                    <input name="password" type="password" id="txtPassword" name="txtPassword" onChange={handleInputChange}/>
                </div>
                <div className="login-row">
                    <button className="login-submit-btn">LOGIN</button>
                </div>
            </form>

            }
        </div>
    );
};

export default LoginForm;