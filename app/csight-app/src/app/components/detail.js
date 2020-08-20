import { default as React, useCallback, useEffect, useState } from 'react';
import { useHistory, useParams, Link } from 'react-router-dom';
import * as Routes from '../routes';

import { default as moment } from 'moment';
import { useApi } from '../services';
import { useAuth } from '../services';
import { apiConfig } from '../config';

const BASE_URL = `${apiConfig.baseURL}`;
const Detail = () => {

    // const { id } = useParams();
    const { findWorksheetById } = useApi();
    const { checkIfLoggedIn, currentUser, getUser } = useAuth();
    const [worksheet, setWorksheet] = useState([])
    
    let history = useHistory();
    
      useEffect(() => {
        redirectToLogin();
        fetchWorksheet();
        return () => {
        }
      }, [checkIfLoggedIn(), currentUser]);

    const redirectToLogin = () => {
        if(checkIfLoggedIn() === false) {
            history.push(Routes.AUTH_SIGN_IN);
        } else {

        }
    }

    const fetchWorksheet = async () => {
        let id = window.location.href.substr(window.location.href.lastIndexOf('/') + 1);
        const data = await findWorksheetById(id);
        setWorksheet(data);
    }
        
    const convertTime = (num) => {
        let hours = Math.floor(num / 60);  
        let minutes = num % 60;
        
        return hours + (minutes == 0 ? "" : ":" + minutes);     
    }

    // Dates
    const convertDateToTime = (date) => {
        return moment(date).format('H:mmu')
    }

    return (
        <div className="detail">
            <div className="detail-template">
            <h1>Een overzicht van je prestaties</h1>
                <div className="detail-list">
                    <p>Dag: {worksheet && moment(worksheet.startDate).format('DD/MM/YYYY')}</p>
                    <p>Start uur: {convertDateToTime(worksheet.startDate)}</p>
                    <p>Eind uur: {convertDateToTime(worksheet.endDate)}</p>
                    <p>Pauze: {worksheet.pause} minuten</p>
                    <p>Gewerkte uren: {convertTime(worksheet.time)}u</p>
                    <p>Activiteiten: {worksheet.activities && worksheet.activities.map((activity, i) => [
                        <div>{activity}</div>
                    ])}</p>
                    
                    <p>Materiaal: {worksheet.materials && worksheet.materials.map((material, i) => [
                        <div>{material}</div>
                    ])}</p>
                    </div>
            </div>
        </div>
    );
};

export default Detail;