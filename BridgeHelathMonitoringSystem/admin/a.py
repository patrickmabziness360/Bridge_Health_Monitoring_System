class StateMachine:
    def __init__(self):
        self.states = {}
        self.current_state = None

    def add_state(self, state_name, state):
        self.states[state_name] = state

    def set_initial_state(self, state_name):
        self.current_state = self.states[state_name]

    def process_input(self, input):
        if self.current_state:
            next_state_name = None

            if input in ["0", "1", "2", "3"]:
                next_state_name = self.current_state.get_next_state(f"go to {'home' if input == '0' else 'work' if input == '1' else 'restaurant' if input == '2' else 'bank'}")
            else:
                next_state_name = self.current_state.get_next_state(input)

            if next_state_name:
                self.current_state = self.states[next_state_name]
                #print("Transitioning to", next_state_name)
            else:
                print("Invalid input!")

    def print_current_state_info(self):
        if self.current_state:
            self.current_state.print_info()
            self.current_state.print_transitions()


class State:
    def __init__(self, name):
        self.name = name
        self.transitions = {}

    def add_transition(self, input, next_state):
        self.transitions[input] = next_state

    def get_next_state(self, input):
        return self.transitions.get(input)

    def print_info(self):
        print("################# You Are In", self.name, "State ###################")

    def print_transitions(self):
        print("++++++++++++++++++ Possible transitions ++++++++++++++++++++++++++")
        print("--------- Choose The Transition You Want To Follow ---------------- ")
        print(list(self.transitions.keys()))
        # for index, transition in enumerate(self.transitions.keys()):
        #     print(f"{index + 1}. {transition}")


if __name__ == "__main__":
    # Create the states
    home = State("Home")
    bank = State("Bank")
    work = State("Work")
    restaurant = State("Restaurant")

    # Transitions to the states
    home.add_transition("go to work", "Work")

    bank.add_transition("go home", "Home")
    bank.add_transition("go to work", "Work")

    work.add_transition("go to bank", "Bank")
    work.add_transition("go to restaurant", "Restaurant")

    restaurant.add_transition("go to work", "Work")

    # Create the state machine
    fsm = StateMachine()
    fsm.add_state("Home", home)
    fsm.add_state("Bank", bank)
    fsm.add_state("Work", work)
    fsm.add_state("Restaurant", restaurant)

    # Set the initial state
    fsm.set_initial_state("Home")

    # Main loop
    while True:
        fsm.print_current_state_info()
        user_input = input("Enter your choice or 'Q' to quit: ")
        user_input = user_input.lower()

        if user_input == "q":
            break

        fsm.process_input(user_input)

    print("Program finished.")
