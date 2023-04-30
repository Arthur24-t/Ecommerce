import { BrowserRouter, Routes, Route } from 'react-router-dom';
import 'bootstrap/dist/css/bootstrap.min.css';
import Homepage from './Pages/Homepage';
import Article from './Pages/Article';
import Unknown from './Pages/Unknown';
import Panier from './Pages/Panier';
import Register from './Pages/Register';
import Login from './Pages/Login';

const App = () => {
  return (
    <BrowserRouter>
    <Routes>
    <Route path='/' element={<Homepage />}/>
    <Route path='/panier' element={<Panier/>}/>
    <Route path='/article' element={<Article/>}/>
    <Route path='/register' element={<Register/>}/>
    <Route path='/login' element={<Login/>}/>
    <Route path="*" element={<Unknown/>} />  
    </Routes>
    </BrowserRouter>
   
  );
}

export default App;
