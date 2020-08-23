import { default as React, useCallback, useEffect, useState } from 'react';
import { useHistory, useParams, Link } from 'react-router-dom';
import * as Routes from '../routes';

import { useApi } from '../services';
import { useAuth } from '../services';
import { apiConfig } from '../config';

const BASE_URL = `${apiConfig.baseURL}`;
const Dasbhoard = () => {

    
    const [worksheetForm, setWorksheetForm] = useState({
        txtEmployee_id: '',
        txtPeriod: '',
        txtStartDate: '',
        txtEndDate: '',
        txtPause: '0',
        txtOvertime: '0',
        txtRate: '',
        txtHour_rate: '',
        txtTransport_cost: '',
        txtActivities: '',
        txtMaterials: '',
    });
    
    const { storeWorksheet, findPeriods } = useApi();
    const { checkIfLoggedIn, currentUser, getUser } = useAuth();
    
    const [periods, setPeriods] = useState()
    
    let history = useHistory();
    
      useEffect(() => {
        redirectToLogin();
        fetchperiods();
        return () => {
          // no cleanup
        }
      }, [checkIfLoggedIn(), currentUser]);

    const redirectToLogin = () => {
        if(checkIfLoggedIn() === false) {
            history.push(Routes.AUTH_SIGN_IN);
        } 

    }
    const fetchperiods = async () => {
        const data = await findPeriods();
        setPeriods(data['hydra:member']);
    }
    


    const handleSubmit = (ev) => {
        ev.preventDefault();
        
        // Calculate time worked
        let timeDiff = Math.abs(new Date(worksheetForm.txtEndDate) - new Date(worksheetForm.txtStartDate) );
        
        const worksheet = {
            employee: "/api/users/" + currentUser.id,
            period: "/api/periods/" + parseInt(worksheetForm.txtPeriod),
            startDate: worksheetForm.txtStartDate,
            endDate: worksheetForm.txtEndDate,
            pause: parseInt(worksheetForm.txtPause),
            hourRate: parseInt(worksheetForm.txtHour_rate),
            transportCost: parseInt(worksheetForm.txtTransport_cost),
            overtime: parseInt(worksheetForm.txtOvertime),
            time: timeDiff/60000 - worksheetForm.txtPause + ((timeDiff/60000 - worksheetForm.txtPause) * worksheetForm.txtRate),
            activities: worksheetForm.txtActivities.split(','),
            materials: worksheetForm.txtMaterials.split(','),
            createdAt: new Date(),
        };

        storeWorksheet(worksheet);
        history.go();
      }

      const handleInputChange = (ev) => {
        setWorksheetForm({
          ...worksheetForm,
          [ev.target.name]: ev.target.value
        });
      }
    
      const handleSelectChange = (ev) => {
        setWorksheetForm({
          ...worksheetForm,
          [ev.target.name]: ev.target.options[ev.target.selectedIndex].value
        });
      }
    
    return (
        <div className="dashboard">
            <div class="worksheet-template">
            <h1>Dashboard</h1>
                <form onSubmit={handleSubmit}>

                    <div class="template-row">
                        <label for="txtPeriod">Periode:</label>
                        <select name="txtPeriod" id="txtPeriod" onChange={handleSelectChange}>
                            { periods && periods.map((period) => (
                                <option value={period.id}>{period.name}</option>
                            )) }
                        </select>
                    </div>
                    <div class="template-row">
                        <label for="txtStartDate">Begin datum:</label>
                        <input name="txtStartDate" id="txtStartDate" onChange={handleInputChange} type="datetime-local"/>
                    </div>
                    <div class="template-row">
                        <label for="txtEndDate">Eind datum:</label>
                        <input name="txtEndDate" id="txtEndDate" onChange={handleInputChange} type="datetime-local"/>
                    </div>

                    <div class="template-row">
                        <label for="txtRate">Extra kost:</label>
                        <select name="txtRate" id="txtRate" onChange={handleSelectChange}>
                            <option value="0">Geen extra kosten</option>
                            <option value="0.5">Zaterdag</option>
                            <option value="1">Zondag</option>
                        </select>
                    </div>

                    <div class="template-row">
                        <label for="txtOvertime">Overuren <small>(minuten)</small>:</label>
                        <input name="txtOvertime" id="txtOvertime" onChange={handleInputChange} value={worksheetForm.txtOvertime} type="number" min="0"/>
                    </div>

                    <div class="template-row">
                        <label for="txtPause">Pauze <small>(minuten)</small>:</label>
                        <input name="txtPause" id="txtPause" onChange={handleInputChange} value={worksheetForm.txtPause} placeholder="0" type="number" min="0"/>
                    </div>

                    {/* Check if the employee is a subtractor */}
                    { currentUser && currentUser.roles.includes("ROLE_SUBTRACTOR") ? 
                    <div>
                        <div class="template-row">
                            <label for="txtHour_rate">Prijs per uur:</label>
                            <input name="txtHour_rate" id="txtHour_rate" onChange={handleInputChange} type="number" min="1"/>
                        </div>
                        <div class="template-row">
                            <label for="txtTransport_cost">Verplaatsingskosten:</label>
                            <input name="txtTransport_cost" id="txtTransport_cost" onChange={handleInputChange} type="number" min="1"/>
                        </div>
                    </div>
                    : ''}
                    <div class="template-row">
                        <label for="txtActivities">Activiteiten:</label>
                        <input name="txtActivities" id="txtActivities" onChange={handleInputChange} type="text"/>
                    </div>
                    <div class="template-row">
                        <label for="txtMaterials">Materiaal:</label>
                        <input name="txtMaterials" id="txtMaterials" onChange={handleInputChange} type="text"/>
                    </div>
                    <input type="submit" value="opslaan"/>
                </form>
            </div>
        </div>
    );
};

export default Dasbhoard;