import React from 'react';
import { useState, useEffect } from 'react';
import axios from 'axios';
import { Row, Col, Card, Button } from 'react-bootstrap';
import Navigation from '../Component/Navigation';

const CartPage = () => {

  // const calculerTotalPanier = () => {
  //   let total = 0;
  //   products.forEach(product => {
  //     total += product.prix;
  //   });
  //   return total;
  // }; 

  const [product, setProduct] = useState([]);
  const [order, setOrder] = useState();

    useEffect(() => {


      const token = localStorage.getItem('token');

    const config = {
      headers: {
        Authorization: `Bearer ${token}` 
      }
    };


        axios.get('http://127.0.0.1:8000/api/carts', config)
        .then(response => {
          setProduct(response.data.data.products)
            // Mettez à jour l'état avec les données de la réponse
          })
          .catch(error => {
            console.error('Erreur lors de la récupération des données :', error);
          });
      }, []);

      const DeleteButton = (id) => {
        const token = localStorage.getItem('token');

        const config = {
          headers: {
            Authorization: `Bearer ${token}` 
          }
        };
    
        axios.delete(`http://127.0.0.1:8000/api/carts/${id}`, config)
        .then(response => {
            // setProduct(response.data.data.products);
          })
          .catch(error => {
            console.error('Erreur lors de la récupération des données :', error);
          });
      };

      const ValidateButton = () => {
        const token = localStorage.getItem('token');

        const config = {
          headers: {
            Authorization: `Bearer ${token}` 
          }
        };

        axios.get(`http://127.0.0.1:8000/api/carts/validate`,config)
        .then(response => {
          // console.log(order);
            // setOrder(response.data);
          })
          .catch(error => {
            console.error('Erreur lors de la récupération des données :', error);
          });
      };
  


  return (
    
    <div>
        <Navigation/>
      <h1>Panier</h1>
      <Row>
        <Col md={8}>
          {product && product.length > 0 ? (
            product.map(product => (
              
              <Card key={product.id} className="mb-3">
                <Row className="align-items-center">
                  <Col md={3}>
                    <Card.Img src={product.photo  } alt={product.nom} />
                  </Col>
                  <Col md={6}>
                    <Card.Body>
                      <Card.Title>{product.name}</Card.Title>
                      <Card.Text>Quantité: {product.quantity} €</Card.Text>
                      <Card.Text>Prix: {product.price} €</Card.Text>
                    </Card.Body>
                  </Col>
                  <Col md={3}>
                    <Button variant="danger" onClick={() => DeleteButton(product.id)}>Supprimer</Button>
                  </Col>
                </Row>
              </Card>
            ))
          ) : (
            <p>Le panier est vide.</p>
          )}
        </Col>
        <Col md={4}>
          <Card>
            <Card.Body>
              <Card.Title>Total</Card.Title>
              {/* <Card.Text>{calculerTotalPanier()} €</Card.Text> */}
              <Button variant="primary" onClick={ValidateButton}>Passer la commande</Button>
            </Card.Body>
          </Card>
        </Col>
      </Row>
      </div>
  );
};

export default CartPage;
