using DCA.Model;
using DCA.Model.Contracts;

namespace DCA.Exchanges.Bitstamp;

public class Buyer : IBuyer<Euro>, IBuyer<Dollar>
{
    Euro IBuyer<Euro>.Buy(Bitcoin amount)
    {
        return new Euro();
    }

    Dollar IBuyer<Dollar>.Buy(Bitcoin amount)
    {
        return new Dollar();
    }
}
