<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

require_once('vendor/autoload.php');

use Shop\Basket\Command\AddProductToTheBasket as AddProductToTheBasketCommand;
use Shop\Basket\Command\ChangeQuantityOfTheProduct;
use Shop\Basket\Command\CloseBasket as CloseBasketCommand;
use Shop\Basket\Command\CreateNewBasket as CreateNewBasketCommand;
use Shop\Basket\Command\Handler\AddProductToTheBasket as AddProductToTheBasketHandler;
use Shop\Basket\Command\Handler\ChangeQuantityOfTheProduct as ChangeQuantityOfTheProductHandler;
use Shop\Basket\Command\Handler\CloseBasket as CloseBasketCommandHandler;
use Shop\Basket\Command\Handler\RemoveProductFromTheBasket as RemoveProductFromTheBasketHandler;
use Shop\Basket\Command\RemoveProductFromTheBasket;
use Shop\Basket\Event\BasketHasBeenClosed as BasketHasBeenClosedEvent;
use Shop\Basket\Event\BasketHasBeenCreated;
use Shop\Basket\Event\ProductHasBeenAddedToTheBasket as ProductHasBeenAddedToTheBasketEvent;
use Shop\Basket\Event\ProductHasBeenRemovedFromTheBasket as ProductHasBeenRemovedFromTheBasketEvent;
use Shop\Basket\Event\QuantityOfTheProductHasBeenChanged as QuantityOfTheProductHasBeenChangedEvent;
use Shop\Basket\Event\Serializer\Handler\BasketHasBeenClosed as BasketHasBeenClosedSerializerHandler;
use Shop\Basket\Event\Serializer\Handler\BasketHasBeenCreatedHandler as BasketHasBeenCreatedSerializerHandler;
use Shop\Basket\Event\Serializer\Handler\ProductHasBeenAddedToTheBasket as ProductAddedToTheBasketSerializerHandler;
use Shop\Basket\Repository\InMemoryRepository as BasketRepository;
use Shop\Command\Bus\CommandBus;
use Shop\Email\Command\Handler\SendEmail as SendEmailCommandHandler;
use Shop\Email\Command\SendEmail as SendEmailCommand;
use Shop\Email\Event\EmailHasBeenSent as EmailHasBeenSentEvent;
use Shop\Email\Event\EmailHasNotBeenSent as EmailHasNotBeenSentEvent;
use Shop\Email\Event\Serializer\Handler\EmailHasBeenSent as EmailHasBeenSentSerializerHandler;
use Shop\Email\Event\Serializer\Handler\EmailHasNotBeenSent as EmailHasNotBeenSentSerializerHandler;
use Shop\Email\Sender\NullEmailSenderService;
use Shop\Event\Repository\InMemoryEventRepository;
use Shop\Event\Serializer\JSONSerializer;
use Shop\Order\Command\CreateOrder as CreateOrderCommand;
use Shop\Order\Command\Handler\CreateOrder as CreateOrderCommandHandler;
use Shop\Order\Event\OrderHasBeenCreated as OrderHasBeenCreatedEvent;
use Shop\Order\Event\Serializer\Handler\OrderHasBeenCreated as OrderHasBeenCreatedEventSerializerHandler;
use Shop\Product\Product;
use Shop\Product\Repository\Command\FindProductByName as FindProductByNameCommand;
use Shop\Product\Repository\Command\Handler\FindProductByName as FindProductByNameCommandHandler;
use Shop\Product\Repository\InMemoryRepository as InMemoryProductRepository;
use Shop\User\Command\ActivateUser as ActivateUserCommand;
use Shop\User\Command\Handler\ActivateUser as ActivateUserCommandHandler;
use Shop\User\Command\Handler\LogInUser as LogInUserCommandHandler;
use Shop\User\Command\Handler\LogOutUser as LogOutUserCommandHandler;
use Shop\User\Command\Handler\RegisterNewUser as RegisterNewUserCommandHandler;
use Shop\User\Command\LogInUser as LogInUserCommand;
use Shop\User\Command\LogOutUser as LogOutUserCommand;
use Shop\User\Command\RegisterNewUser as RegisterNewUserCommand;
use Shop\User\Event\ActivationTokenHasBeenGenerated as ActivationTokenHasBeenGeneratedEvent;
use Shop\User\Event\Serializer\Handler\ActivationTokenHasBeenGenerated as ActivationTokenHasBeenGeneratedSerializerHandler;
use Shop\User\Event\Serializer\Handler\UnsuccessfulAttemptOfActivatingUserAccount as UnsuccessfulAttemptOfActivatingUserAccountEventSerializerHandler;
use Shop\User\Event\Serializer\Handler\UserAccountHasBeenActivated as AttemptOfActivatingUserAccountSerializerHandler;
use Shop\User\Event\Serializer\Handler\UserHasBeenLoggedIn as UserHasBeenLoggedInEventSerializerHandler;
use Shop\User\Event\Serializer\Handler\UserHasBeenLoggedOut as UserHasBeenLoggedOutEventSerializerHandler;
use Shop\User\Event\UnsuccessfulAttemptOfActivatingUserAccount as UnsuccessfulAttemptOfActivatingUserAccountEvent;
use Shop\User\Event\UserAccountHasBeenActivated as AttemptOfActivatingUserAccountEvent;
use Shop\User\Event\UserHasBeenLoggedIn as UserHasBeenLoggedInEvent;
use Shop\User\Event\UserHasBeenLoggedOut as UserHasBeenLoggedOutEvent;
use Shop\User\Event\UserHasBeenRegistered as UserHasBeenRegisteredEvent;
use Shop\User\Repository\InMemoryUserRepository as InMemoryUserRepository;
use Shop\UUID\RamseyGenerator;

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

/* Dependency Injection Container */

$uuidGenerator = new RamseyGenerator();
$emailSenderService = new NullEmailSenderService(true);

$serializer = new JSONSerializer();
$serializer->registerHandler(BasketHasBeenCreated::class, new BasketHasBeenCreatedSerializerHandler());
$serializer->registerHandler(ProductHasBeenAddedToTheBasketEvent::class, new ProductAddedToTheBasketSerializerHandler());
$serializer->registerHandler(QuantityOfTheProductHasBeenChangedEvent::class, new \Shop\Basket\Event\Serializer\Handler\QuantityOfTheProductHasBeenChanged());
$serializer->registerHandler(ProductHasBeenRemovedFromTheBasketEvent::class, new \Shop\Basket\Event\Serializer\Handler\ProductHasBeenRemovedFromTheBasket());
$serializer->registerHandler(UserHasBeenRegisteredEvent::class, new \Shop\User\Event\Serializer\Handler\UserHasBeenRegistered());
$serializer->registerHandler(ActivationTokenHasBeenGeneratedEvent::class, new ActivationTokenHasBeenGeneratedSerializerHandler());
$serializer->registerHandler(EmailHasBeenSentEvent::class, new EmailHasBeenSentSerializerHandler());
$serializer->registerHandler(EmailHasNotBeenSentEvent::class, new EmailHasNotBeenSentSerializerHandler());
$serializer->registerHandler(AttemptOfActivatingUserAccountEvent::class, new AttemptOfActivatingUserAccountSerializerHandler());
$serializer->registerHandler(UnsuccessfulAttemptOfActivatingUserAccountEvent::class, new UnsuccessfulAttemptOfActivatingUserAccountEventSerializerHandler());
$serializer->registerHandler(UserHasBeenLoggedInEvent::class, new UserHasBeenLoggedInEventSerializerHandler());
$serializer->registerHandler(UserHasBeenLoggedOutEvent::class, new UserHasBeenLoggedOutEventSerializerHandler());
$serializer->registerHandler(OrderHasBeenCreatedEvent::class, new OrderHasBeenCreatedEventSerializerHandler());
$serializer->registerHandler(BasketHasBeenClosedEvent::class, new BasketHasBeenClosedSerializerHandler());

$eventRepository = new InMemoryEventRepository($serializer);

$commandBus = new CommandBus($uuidGenerator, $eventRepository);
$commandBus->registerHandler(CreateNewBasketCommand::class, new \Shop\Basket\Command\Handler\CreateNewBasket($uuidGenerator));
$commandBus->registerHandler(AddProductToTheBasketCommand::class, new AddProductToTheBasketHandler($uuidGenerator));
$commandBus->registerHandler(ChangeQuantityOfTheProduct::class, new ChangeQuantityOfTheProductHandler($uuidGenerator));
$commandBus->registerHandler(RemoveProductFromTheBasket::class, new RemoveProductFromTheBasketHandler($uuidGenerator));
$commandBus->registerHandler(RegisterNewUserCommand::class, new RegisterNewUserCommandHandler($uuidGenerator));
$commandBus->registerHandler(SendEmailCommand::class, new SendEmailCommandHandler($uuidGenerator));
$commandBus->registerHandler(ActivateUserCommand::class, new ActivateUserCommandHandler($uuidGenerator));
$commandBus->registerHandler(LogInUserCommand::class, new LogInUserCommandHandler($uuidGenerator));
$commandBus->registerHandler(FindProductByNameCommand::class, new FindProductByNameCommandHandler($uuidGenerator));
$commandBus->registerHandler(LogOutUserCommand::class, new LogOutUserCommandHandler($uuidGenerator));
$commandBus->registerHandler(CreateOrderCommand::class, new CreateOrderCommandHandler($uuidGenerator));
$commandBus->registerHandler(CloseBasketCommand::class, new CloseBasketCommandHandler($uuidGenerator));

$basketRepository = new BasketRepository($serializer);
$userRepository = new InMemoryUserRepository($serializer);

$hashGenerator = new \Shop\Password\HashGenerator();

$productRepository = new InMemoryProductRepository();
$productRepository->save(new Product($uuidGenerator->generate(), 'Milk'));

$breadId = $uuidGenerator->generate();
$productRepository->save(new Product($breadId, 'Bread'));

$butterUuid = $uuidGenerator->generate();
$productRepository->save(new Product($butterUuid, 'Butter'));

/* Controller */

$registerUserCommand = new RegisterNewUserCommand('user@user.com', 'password', $emailSenderService, new \Shop\Generator\ActivationTokenGenerator(), $uuidGenerator, new \Shop\Password\SaltGenerator(), $hashGenerator);
$commandBus->handle($registerUserCommand);
$user = $commandBus->getOutputForCommand($registerUserCommand);

$activateUserCommand = new ActivateUserCommand('user@user.com', 'xxx', $userRepository); //attempt of activating user account with wrong token
$commandBus->handle($activateUserCommand);

$activateUserCommand = new ActivateUserCommand('user@user.com', $user->getActivationToken(), $userRepository);
$commandBus->handle($activateUserCommand);

$activateUserCommand = new ActivateUserCommand('user@user.com', $user->getActivationToken(), $userRepository); //attempt of activating already activated account
$commandBus->handle($activateUserCommand);

$logInUserCommand = new LogInUserCommand('user@user.com', 'password', $hashGenerator, $userRepository);
$commandBus->handle($logInUserCommand);

$findProductByNameCommand = new FindProductByNameCommand($user, 'Milk', $productRepository);
$commandBus->handle($findProductByNameCommand);
$milk = $commandBus->getOutputForCommand($findProductByNameCommand);

$createNewBasket = new CreateNewBasketCommand($uuidGenerator, $user->getEmail());
$commandBus->handle($createNewBasket);
$basket = $basketRepository->findLastBasketByUserEmail($user->getEmail());

$addProductToTheBasket = new AddProductToTheBasketCommand($basket, $milk, 2.0);
$commandBus->handle($addProductToTheBasket);

$findProductByNameCommand = new FindProductByNameCommand($user, 'Bread', $productRepository);
$commandBus->handle($findProductByNameCommand);
$bread = $commandBus->getOutputForCommand($findProductByNameCommand);

$addProductToTheBasket = new AddProductToTheBasketCommand($basket, $bread, 1.0);
$commandBus->handle($addProductToTheBasket);

$findProductByNameCommand = new FindProductByNameCommand($user, 'Butter', $productRepository);
$commandBus->handle($findProductByNameCommand);
$butter = $commandBus->getOutputForCommand($findProductByNameCommand);

$addProductToTheBasket = new AddProductToTheBasketCommand($basket, $butter, 3.0);
$commandBus->handle($addProductToTheBasket);

$changeQuantityOfTheProduct = new ChangeQuantityOfTheProduct($basket, $butterUuid, 1.0);
$commandBus->handle($changeQuantityOfTheProduct);

$removeProductFromTheBasket = new RemoveProductFromTheBasket($basket, $breadId);
$commandBus->handle($removeProductFromTheBasket);

$logOutUserCommand = new LogOutUserCommand($user);
$commandBus->handle($logOutUserCommand);

$logInUserCommand = new LogInUserCommand('user@user.com', 'password', $hashGenerator, $userRepository);
$commandBus->handle($logInUserCommand);

$createOrderCommand = new CreateOrderCommand($uuidGenerator, $basket, $emailSenderService, new \Shop\Email\Email($uuidGenerator->generate()));
$commandBus->handle($createOrderCommand);

///** Recreating the basket */
dump(InMemoryEventRepository::$memory);
$user = $userRepository->findUserByEmail('user@user.com');
$basket = $basketRepository->findLastBasketByUserEmail($user->getEmail());
dump($user);
dump($basket);