import { default as React, useCallback, useEffect, useState } from 'react';
import { useHistory, useParams, Link } from 'react-router-dom';
import * as Routes from '../routes';

import { default as moment } from 'moment';
import { useApi } from '../services';
import { useAuth } from '../services';
import { apiConfig } from '../config';

const BASE_URL = `${apiConfig.baseURL}`;
const Overview = () => {

    const { findWorksheetsByUserId } = useApi();
    const { checkIfLoggedIn, currentUser, currentUserId } = useAuth();
    const [worksheets, setWorksheets] = useState('')
    
    let history = useHistory();
    
      useEffect(() => {
        redirectToLogin();
        fetchWorksheets(currentUser.id);
        return () => {
        }
      }, [checkIfLoggedIn(), currentUser]);

    const redirectToLogin = () => {
        if(checkIfLoggedIn() === false) {
            history.push(Routes.AUTH_SIGN_IN);
        } else {

        }
    }
    const fetchWorksheets = async (id) => {
        const data = await findWorksheetsByUserId(id);
        setWorksheets(data['hydra:member']);
    }
    
        
    const convertTime = (num) => {
        let hours = Math.floor(num / 60);  
        let minutes = num % 60;
        
        return hours + (minutes == 0 ? "" : ":" + minutes);     
    }

    return (
        <div className="overview">
            <div className="overview-template">
                <h1>Een overzicht van je prestaties</h1>
                <div className="overview-list">
                    <table>
                        <tr>
                            <th>#</th>
                            <th>Dag</th>
                            <th>Gewerkte tijd</th>
                            <th>Pause</th>
                            <th>Details</th>
                        </tr>
                        { worksheets && worksheets.map((worksheet) => (
                        <tr>
                            <td>{worksheet.id}</td>
                            <td>{ moment(worksheet.startDate).format('DD/MM/YYYY') }</td>
                            <td>{ convertTime(worksheet.time) }u</td>
                            <td>{worksheet.pause} minuten</td>
                            <Link to={{pathname: `/overview/detail/${worksheet.id}`}}>Bekijken</Link>
                        </tr>
                        )) }
                    </table>

                </div>

            </div>
        </div>
    );
};

export default Overview;