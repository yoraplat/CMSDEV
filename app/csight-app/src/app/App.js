import React from 'react';
import { BrowserRouter as Router, Switch, Route, Link, useRouteMatch, useParams } from "react-router-dom";

import './sass/App.scss';
import { SignInPage } from './pages';
import { LoginForm } from './components';
import { Dashboard } from './components';
import { Overview } from './components';
import { Detail } from './components';

import { ApiProvider, AuthProvider } from './services';

import * as Routes from './routes';
function App() {

  return (
    <div className="App">
      <ApiProvider>
      <AuthProvider>
        <Router>
          <div>
            <ul className="main-nav">
                <Link to="/">Home</Link>
                <Link to="/overview">Overzicht</Link>
                <Link to="/auth/signin">Login</Link>
            </ul>

            <Switch>
              <Route path={Routes.AUTH_SIGN_IN}>
                <LoginForm/>
              </Route>
              <Route path={Routes.DETAIL} >
                <Detail />
              </Route>
              <Route path={Routes.OVERVIEW}>
                <Overview />
              </Route>
              <Route path={Routes.LANDING}>
                <Dashboard />
              </Route>
            </Switch>
          </div>
        </Router>
      </AuthProvider>
      </ApiProvider>
    </div>
  );
  
}

export default App;
