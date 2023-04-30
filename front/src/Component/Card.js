  import { useState, useEffect } from "react";
import axios from 'axios';
import { Card } from 'react-bootstrap';

const CardComponent = ({data}) => {

  // data = data.data;
  // const [product, setProduct] = useState({});
  // // const [name, setName] = useState('');
  // // const [price, setPrice] = useState('');
  // // const [description, setDescription] = useState('');
  // // const [photo, setPhoto] = useState('');


  useEffect(() => {
    console.log(data);
  }, []);

  const handleButton = () => {

    const token = localStorage.getItem('token');
    const config = {
      headers: {
        Authorization: `Bearer ${token}` 
      }
    };
      axios.post(`http://127.0.0.1:8000/api/carts/${data.id}`, null, config)
      .then(response => {
          console.log(response.data);
        })
        .catch(error => {
          console.error('Erreur lors de la récupération des données :', error);
        });
    };

  return (
    <Card className="mx-auto" style={{ width: '22rem' }}>
      <Card.Img variant="top" src={data.photo} />
      <Card.Body>
        <Card.Title>{data.name}</Card.Title>
        <Card.Text>
          {data.description} 
        </Card.Text>
        <Card.Text>
          {data.price}€ 
        </Card.Text>
        <Card.Link href="#" onClick={handleButton} >Ajouter au panier</Card.Link><br></br>
      </Card.Body>
    </Card>
  );
};

export default CardComponent;
