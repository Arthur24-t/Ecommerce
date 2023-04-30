import { useState, useEffect } from "react";
import CardComponent from "../Component/Card";
import Navigation from "../Component/Navigation";
import axios from 'axios';
import { Row, Col } from 'react-bootstrap';

const Homepage = () => {

    const [product, setProduct] = useState([]);

    useEffect(() => {
        axios.get('http://127.0.0.1:8000/api/products')
        .then(response => {
            // Mettez à jour l'état avec les données de la réponse
            setProduct(response.data);
          })
          .catch(error => {
            console.error('Erreur lors de la récupération des données :', error);
          });
      }, []);


    if (!product || product.length === 0) {
        // Gérer le cas où cartes est undefined ou vide
        return <div>
            <Navigation/>
            Aucun article n'est disponible.
            </div>;
      }
    return (  
    <div>
        <Navigation/>
        <div>
        <Row>
            {product.map((product) =>
                <Col sm={4} key={product.id}>
            <CardComponent data={product}/>
            </Col>
 )}
            
        </Row>
        </div>
        
        
    </div>
    );
}
 
export default Homepage;
<div>

</div>
