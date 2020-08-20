import { default as React, useContext, createContext } from 'react';

import { apiConfig } from '../config';

const ApiContext = createContext();
const useApi = () => useContext(ApiContext);

const ApiProvider = ({children}) => {
  const BASE_URL = `${apiConfig.baseURL}`;

  const findUsers = async () => {
    let url = `${BASE_URL}/users`;
    const response = await fetch(url);
    return response.json();
  }
  
  const findPeriods = async () => {
    let url = `${BASE_URL}/periods`;
    const response = await fetch(url);
    return response.json();
  }
  
  const findUserById = async (id) => {
    let url = `${BASE_URL}/users/${id}`;
    const response = await fetch(url);
    return response.json();
  }

  const storeWorksheet = async (worksheet) => {
    const options = {
      method: "post",
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(worksheet)
    };
    let url = `${BASE_URL}/worksheets`;
    const response = await fetch(url, options);
    return response.json();
  }

  const findWorksheetsByUserId = async (id) => {
    let url = `${BASE_URL}/users/${id}/user_worksheets`;
    const response = await fetch(url);
    return response.json();
  }

  const findWorksheetById = async (id) => {
    let url = `${BASE_URL}/worksheets/${id}`;
    const response = await fetch(url);
    return response.json();
  }

  return (
    <ApiContext.Provider value={{ findUsers, findUserById, storeWorksheet, findPeriods, findWorksheetsByUserId, findWorksheetById }}>
      {children}
    </ApiContext.Provider>
  );
};

export {
  ApiContext,
  ApiProvider,
  useApi,
}